# UCC Events - Backend

Állásinterjúhoz tartozó projektfeladat backendjének repository-ja.

[![Made by Molnár Márton](https://img.shields.io/badge/Made%20by-Molnár%20Márton-orange)](https://cybrcrime.hu)

## Előfeltételek
A backend futtatásához szükség van egy Meilisearch deploymentre. Fejlesztői környezetben ehhez a legegyszerűbb mód egy Docker konténer elindítása az alábbi módon

```shell
docker pull getmeili/meilisearch:latest

docker run -it --rm \
    -p 7700:7700 \
    -e MEILI_ENV='development' \
    getmeili/meilisearch:latest
```

## Projekt elindítása saját gépen

- Clone-ozd le a repot a saját gépedre
- Készíts egy másolatot a .env.example állományról .env néven `cp .env.example .env`
- Telepítsd a szükséges dependenciákat `composer install`
- Hozz létre egy alkalazás szintű titkosító kulcsot `php artisan key:generate`
- Aállítsd be a .env állományt az alábbiak szerint (az itt nem említett változók változatlanul maradhatnak)
```dotenv
BROADCAST_CONNECTION="reverb" # Ahhoz, hogy a backend által küldött események megérkezzenek a frontendre, a BROADCAST_CONNECTION változó értékének a reverb-et kell megadni

# Abban az esetben, ha van SMTP szervered, akkor az alábbi változók beállításával megadhatod, hogy a kimenő emailek azon keresztül kerüljenek kiküldésre. Alapértelmezetten a levelek a storagr/logs/laravel.log file-ba kerülnek kiíratásra
MAIL_MAILER=log
MAIL_SCHEME=null
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

#Reverb beállítások
REVERB_SERVER="reverb" # Laravel Reverb szerver típusa
REVERB_SERVER_HOST="0.0.0.0" # Az az IP cím, amin keresztül a Reverb szerver figyeljen, 0.0.0.0 esetén a szerver összes IP címén keresztül figyel a forgalomra
REVERB_SERVER_PORT="8080" # A Reverb szerver portja

# Az alábbi beállítások a backend Reverb szerverhez való csatlakkozásához szükségesek
REVERB_HOST="localhost"
REVERB_SCHEME="http"
REVERB_PORT="8080"

# Az alábbi környekezi változókban beállított értéket kell az alkalmazás frontendjének megadni, hogy csatlakozni tudjon a Reverb szolgáltatáshoz
REVERB_APP_KEY="frontend"
REVERB_APP_SECRET="frontend"
REVERB_APP_ID="frontend"

FRONTEND_PASSWORD_RESET_URL="http://localhost/login/reset-password" # A Frontend azon címe, ami kiküldésre kerül a jelszóhelyreállító emailekben

# Laravel Scout beállítások
SCOUT_DRIVER=meilisearch
MEILISEARCH_HOST=http://localhost:7700
MEILISEARCH_KEY=
```
- Adatbázis létrehozása, migrációk futtatása és kezdeti adatok betöltése
```shell
php artisan migrate --seed
```
> Abban az esetben, ha a fenti parancs megkérdezi, hogy az adatbázis még nem létezik, létre kívánod-e hozni, akkor válaszd az igen (yes/y) lehetőséget.
- Passport titkosító kulcsok létrehozása
```shell
php artisan passport:keys
```
- Passport kliens létrehozása
```shell
php artisan passport:client --password --name frontend
```
> A megjelenő kliens id-t és secret-et kell megadni a frontend alkalmazásnak. **Fontos**, hogy ezeket az adatokat a backend többet nem jeleníti meg.
- Meilisearch adatok betöltése
```shell
php artisan scout:sysnc-index-settings && php artisan scout:import "App\Models\Faq"
```
- Első user létrehozása
```shell
php artisan user:create
```
> A parancs kiadása után meg kell adni az új felhasználó adatait: név, email cím és jelszó. **Fontos**, hogy a jelszót a rendszer nem írja ki a konzolra, így azt jól jegyezd meg.

- Reverb indítása
```shell
php artisan reverb:start
```
> A Reverb szolgáltatásnak folyamatosan működőképesnek kell lennie, különben a backend működésképtelenségét vonhatja maga után.

- Queue indítása
```shell
php artisan queue:work
```
> A Queue szolgáltatásnak folyamatosan működőképesnek kell lennie, különben a backend működésképtelenségét vonhatja maga után.

- Backend indítása
```shell
php artisan serve
```
