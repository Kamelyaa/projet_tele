import pyotp

# Générer un secret aléatoire
secret = pyotp.random_base32()

# Enregistrer le secret dans un fichier
with open("secret_code.txt", "w") as file:
    file.write(secret)

# Afficher le secret
print("Secret partagé :", secret)
