import redis

try:
    # Remplacez l'adresse IP par celle trouvée dans WSL
    r = redis.Redis(host='172.25.21.65', port=6379)  # Remplacez par votre IP
    r.ping()
    print("Redis est en ligne.")
except redis.exceptions.ConnectionError as e:
    print(f"Erreur de connexion : {e}")
except Exception as e:
    print(f"Une erreur s'est produite : {e}")
