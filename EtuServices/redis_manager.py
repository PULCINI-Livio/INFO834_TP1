import redis
import time
import sys
import logging

# Configurer le logging pour enregistrer dans un fichier
logging.basicConfig(filename='connection.log', level=logging.DEBUG)

# Connexion à Redis
try:
    r = redis.StrictRedis(host='localhost', port=6379, db=0)
    r.ping()  # Vérifie si Redis est accessible
except redis.ConnectionError as e:
    print(f"Erreur de connexion à Redis: {e}")
    sys.exit(1)

# Fonction pour gérer les connexions utilisateur
def check_and_increment_connections(user_email):
    # La clé Redis sera l'email de l'utilisateur
    user_key = f"connections:{user_email}"
    
    # La fenêtre de 10 minutes (600 secondes)
    window_size = 600
    
    # Le nombre maximum de connexions autorisées dans la fenêtre de 10 minutes
    max_connections = 10
    
    # Obtenir le nombre de connexions de l'utilisateur dans la fenêtre de 10 minutes
    current_time = time.time()
    logging.info(f"Vérification des connexions pour {user_email} à {current_time}")

    # Supprime les connexions plus anciennes que 10 minutes
    r.zremrangebyscore(user_key, 0, current_time - window_size)
    logging.info(f"Connexions après nettoyage : {r.zcard(user_key)}") 

    # Ajouter l'heure actuelle de la connexion dans le set trié
    r.zadd(user_key, {current_time: current_time})
    logging.info(f"Connexions après ajout : {r.zcard(user_key)}")

    # Vérifier combien de connexions existent dans la fenêtre de 10 minutes
    connection_count = r.zcard(user_key)
    logging.info(f"Nombre de connexions dans les 10 dernières minutes : {connection_count}")
    
    # Maj liste des 10 derniers comptes connectés
    # Supprime l'email s'il est déjà dans la liste
    r.lrem('recent_users', 0, user_email)

    # Ajoute l'email au début de la liste
    r.lpush('recent_users', user_email)

    # Limite la liste aux 10 derniers éléments
    r.ltrim('recent_users', 0, 9)
    logging.info("recent_users updated")


    # Incrémentation du total de connexion de l'utilisateur
    r.zincrby('user_activity', 1, user_email);  


    # Si l'utilisateur dépasse le nombre autorisé, la connexion est refusée
    if connection_count > max_connections:
        print("Trop de connexions. Accès refusé.")
        print(f"Nombre de connexions dans les 10 dernières minutes : {connection_count}")
        return False
    
    #print("Connexion autorisée.")
    return True


"""# Récupérer l'email de l'utilisateur passé en argument
if len(sys.argv) != 2:
    print("Erreur : l'email de l'utilisateur doit être passé en argument.")
    sys.exit(1)"""

if sys.argv[1] == "check_and_increment_connections":
    user_email = sys.argv[2]
    # Vérifier si l'utilisateur peut se connecter
    if check_and_increment_connections(user_email):
        print("OK")
    else:
        print("Trop de connexions. Accès refusé.")


# Fonction pour gérer les connexions utilisateur
def increment_achat_vente(user_email, service):
    r.hincrby('user_connections', f"{user_email} : {service}'", 1)    # Incrémenter le nombre de connexions dépendant du service
    logging.info(f"user_connections updated, {user_email} incremented {service} by 1")


if sys.argv[1] == "incremente_achat_vente":
    user_email = sys.argv[2]
    service = sys.argv[3]
    increment_achat_vente(user_email, service)

def afficher_statistiques():
    print("10 derniers utilisateurs connectés (more recent first):")
    for i in range(10):
        print(r.lrange("recent_users", i, i))

    print("3 premiers utilisateurs les plus actifs")
    print(r.zrevrange('user_activity', 0, 2))  # Récupère les 3 premiers utilisateurs les plus actifs

    print("service le plus utilisé")
    print(r.zrevrange('service_usage', 0, 0))  # Récupère le service le plus utilisé

    print("3 utilisateurs les moins actifs")
    print(r.zrange('user_activity', 0, 2))  # Récupère les 3 utilisateurs les moins actifs


if sys.argv[1] == "afficher_statistiques":
    afficher_statistiques()


