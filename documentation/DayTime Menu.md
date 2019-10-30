# Get the specific time menu of a specific day of the week

It returns the specific time menu of a specific day of the week in a json format.

**URL** : `/[DAY]/[TIME]`

**Method** : `GET`

**Data constraints** : 

```
- 'DAY' must be one of the following items: 'lunedi', 'martedi', 'mercoledi', 'giovedi', 'venerdi', 'sabato', 'domenica'
- 'TIME' must be one of the following items: 'pranzo', 'cena'
```

## Success Response

**Condition** : If the current week menu is present on the server, the entered day is correct and the time entered is allowed

**Code** : `200 OK`

**Content example**

```json
{
"status": "Success",
  "menu": {
    "pranzo/cena": {
              "primi": [],
              "secondi": [],
              "contorni": []
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
----

**Condition** : If the current week menu is present on the server but the day is incorrect

**Code** : `404 NOT FOUND`

**Content example**
```json
{
  "status": "Error",
  "message": "Il giorno inserito non esiste."
}
```

----

**Condition** : If the current week menu is present on the server, the day is incorrect but the time is not allowed

**Code** : `404 NOT FOUND`

**Content example**

```json
{
  "status": "Error",
  "message": "L'orario inserito non esiste."
}
```
