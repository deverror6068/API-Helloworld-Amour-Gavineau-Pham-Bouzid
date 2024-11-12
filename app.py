
from flask import Flask, request, jsonify, Response

app = Flask(__name__)

# Exercice 1 : /hello endpoint
@app.route('/hello', methods=['GET'])
def hello():
    return jsonify(message="Hello World!")



# Exécution de l'application
if __name__ == '__main__':
    app.run(debug=True)
