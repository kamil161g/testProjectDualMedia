Sklonuj repozytorium na swoją lokalną maszynę:

git clone https://github.com/kamil161g/testProjectDualMedia Przejdź do katalogu aplikacji:cd [nazwa-katalogu-aplikacji] Uruchom kontenery Docker za pomocą Docker Compose:

docker-compose up --build Flagę --build używamy, aby upewnić się, że obrazy Docker zostaną zbudowane przed uruchomieniem. Jest to szczególnie przydatne przy pierwszym uruchomieniu lub gdy dokonano zmian w konfiguracji Docker.

Komende odpalamy w kontenrze testprojectdualmedia_php_1.

Użyj docker ps znajdz swoj kontener i wejdz w niego np: docker exec -it  testprojectdualmedia_php_1 /bin/bash

Po uruchomieniu kontenera pierwszym krokiem powinno być zainstalowanie zależności projektu za pomocą Composera. Aby to zrobić, musisz wejść do kontenera, w którym zainstalowany jest PHP, jak opisano w poprzedniej wiadomości. 

Po wejściu do kontenera wykonaj poniższe polecenie, aby zainstalować zależności:

composer install

Aby uruchomić testy Twojej aplikacji, użyj PHPspec, który jest jednym z narzędzi do testowania zachowań. Testy PHPspec możesz uruchomić bezpośrednio z kontenera. Po zainstalowaniu zależności, w tym samym kontenerze, wykonaj:

php vendor/bin/phpspec run lub vendor/bin/phpspec run

Z baza łaczymy sie po danych:

127.0.0.1
symfony
symfony
3308

strona dostepna pod: http://localhost:8888/

dokumetnacja dla requestów: https://documenter.getpostman.com/view/8640351/2sA3JKeNSK
