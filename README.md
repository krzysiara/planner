Event Planner
===
K. Raputa
------------

Instrukcja instalacji
----------------------

1. Pobierz projekt przy pomocy gita lub rozpakuj repozytorium poza folderem public_html.
2. Wywołaj polecenie ```composer install```.
3. Dodaj połączenie symboliczne pomiędzy katalogiem \web aplikacji, a katalogiem public_html
4. W katalogu aplikacji wywołaj polecenia:
 ```
 php app/console doctrine:schema:update --force
 php app/console doctrine:fixtures:load
 php app/console assetic:dump
 ```
 5. Następnie dodaj użytkownika z uprawnieniami administratora:
  ```
 php app/console fos:user:create admin
 ```