import redis
import time
import sys
import logging

# Configurer le logging pour enregistrer dans un fichier
logging.basicConfig(filename='services.log', level=logging.DEBUG)

# Connexion à Redis
try:
    r = redis.StrictRedis(host='localhost', port=6379, db=0)
    r.ping()  # Vérifie si Redis est accessible
except redis.ConnectionError as e:
    print(f"Erreur de connexion à Redis: {e}")
    sys.exit(1)

# Fonction pour gérer les connexions utilisateur
def increment_achat_vente(user_email, service):
    print(user_email)
    print(service)

user_email = sys.argv[1]
service = sys.argv[2]
increment_achat_vente(user_email, service)