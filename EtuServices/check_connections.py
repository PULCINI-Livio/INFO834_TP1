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
    #print(f"Vérification des connexions pour {user_email} à {current_time}")
    
    # Supprime les connexions plus anciennes que 10 minutes
    r.zremrangebyscore(user_key, 0, current_time - window_size)
    #print(f"Connexions après nettoyage : {r.zcard(user_key)}")
    
    # Ajouter l'heure actuelle de la connexion dans le set trié
    r.zadd(user_key, {current_time: current_time})
    #print(f"Connexions après ajout : {r.zcard(user_key)}")
    
    # Vérifier combien de connexions existent dans la fenêtre de 10 minutes
    connection_count = r.zcard(user_key)
    #print(f"Nombre de connexions dans les 10 dernières minutes : {connection_count}")

    logging.info(user_key)
    
    # Si l'utilisateur dépasse le nombre autorisé, la connexion est refusée
    if connection_count > max_connections:
        print("Trop de connexions. Accès refusé.")
        print(f"Nombre de connexions dans les 10 dernières minutes : {connection_count}")
        return False
    
    #print("Connexion autorisée.")
    return True

# Récupérer l'email de l'utilisateur passé en argument
if len(sys.argv) != 2:
    print("Erreur : l'email de l'utilisateur doit être passé en argument.")
    sys.exit(1)

user_email = sys.argv[1]

# Vérifier si l'utilisateur peut se connecter
if check_and_increment_connections(user_email):
    print("OK")
else:
    print("Trop de connexions. Accès refusé.")
