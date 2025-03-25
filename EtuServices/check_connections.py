import redis
import time

# Connexion à Redis
r = redis.StrictRedis(host='localhost', port=6379, db=0)

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
    # Supprime les connexions plus anciennes que 10 minutes
    r.zremrangebyscore(user_key, 0, current_time - window_size)
    
    # Ajouter l'heure actuelle de la connexion dans le set trié
    r.zadd(user_key, {current_time: current_time})
    
    # Vérifier combien de connexions existent dans la fenêtre de 10 minutes
    connection_count = r.zcard(user_key)
    
    # Si l'utilisateur dépasse le nombre autorisé, la connexion est refusée
    if connection_count > max_connections:
        return False
    
    return True

# Exemple d'utilisation
if __name__ == "__main__":
    user_email = "user@example.com"  # Exemple d'email de l'utilisateur
    
    if check_and_increment_connections(user_email):
        print("Connexion autorisée.")
    else:
        print("Trop de connexions. Accès refusé.")
