# Get the entire week menu

It returns the entire week menu in a json format.

**URL** : `/`

**Method** : `GET`

## Success Response

**Condition** : If the current week menu is present on the server

**Code** : `200 OK`

**Content example**

```json
{
"status": "Success",
  "menu": {
    "lunedi": {
        "pranzo": {
              "primi": [],
              "secondi": [],
              "contorni": []
        },
        "cena": {
              "primi": [],
              "secondi": [],
              "contorni": []
        }
   },
    "martedi": {
        "pranzo": {
              "primi": [],
              "secondi": [],
              "contorni": []
        },
        "cena": {
              "primi": [],
              "secondi": [],
              "contorni": []
        }
    },
    "mercoledi": {
        "pranzo": {
              "primi": [],
              "secondi": [],
              "contorni": []
        },
        "cena": {
              "primi": [],
              "secondi": [],
              "contorni": []
        }
    },
    "giovedi": {
        "pranzo": {
              "primi": [],
              "secondi": [],
              "contorni": []
        },
        "cena": {
              "primi": [],
              "secondi": [],
              "contorni": []
        }
    },
    "venerdi": {
        "pranzo": {
              "primi": [],
              "secondi": [],
              "contorni": []
        },
        "cena": {
              "primi": [],
              "secondi": [],
              "contorni": []
        }
    },
    "sabato": {
        "pranzo": {
              "primi": [],
              "secondi": [],
              "contorni": []
        },
        "cena": {
              "primi": [],
              "secondi": [],
              "contorni": []
        }
    },
    "domenica": {
        "pranzo": {
              "primi": [],
              "secondi": [],
              "contorni": []
        },
        "cena": {
              "primi": [],
              "secondi": [],
              "contorni": []
        }
    }
  }
}
```

## Error Responses

**Condition** : If the current week menu is not present on the server

**Code** : `404 NOT FOUND`

**Content example**

```json
{
  "status": "Error",
  "message": "Il menu di questa settimana non è stato trovato, probabilmente l'ERSU non lo ha ancora pubblicato."
}
```
