<?php

namespace Database\Seeders;

use App\Models\Faq;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Faq::factory()->createMany([
            [
                "question" => "Hogyan tudok létrehozni új eseményt?",
                "answer" => "Új esemény létrehozásához az oldal tetején található menüből válassza ki az \"Eseményeim\" menüpontot. A megjelenő oldalon kattintson a + jelet tartalmazó téglalapra. Ekkor megjelenik az új esemény felviteléhez szükséges ürlap, ahol a leírás kivételével minden mező kitöltése kötelező.",
                "tags" => json_encode(["új","létrehozás","felvitel","létre","hozni","létrehozni"])
            ],
            [
                "question" => "Hogyan tudok szerkeszteni egy meglévő eseményt?",
                "answer" => "Az \"Eseményeim\" menüpontot választva listázhatja az Ön által létrehozott eseményeket.A megjelenő téglalapokon válassza ki a szerkesztés lehetőséget. Ekkor megjelenik az az ürlap, ahol módosítani tudja a kiválasztott eseményt.",
                "tags" => json_encode(["módosítás","szerkesztés","megváltoztatás", "megváltoztatni"])
            ],
            [
                "question" => "Hogyan lehet eseményt törölni?",
                "answer" => "Az oldal kezdőoldalán található eseményeit listázó téglalapokban található \"Törlés\" gombra kattintva törölheti az eseményt. Vigyázzon! Ez a művelet NEM visszavonható.",
                "tags" => json_encode(["törlés","eltávolítás","eldobás"])
            ],
            [
                "question" => "Kérhetek ügyintézőt?",
                "answer" => "Ügyfélszolgálatunk jelenleg nem elérhető, így most csak az automatizált helpdesken keresztül kaphat választ kérdéseire.",
                "tags" => json_encode(["ember","ügyintéző","élő","személy","online","agent","ügyfélszolgálat"])
            ],
        ]);

        if(User::where("name","Meilisearch")->get()->isEmpty()){
            User::factory()->create([
                "name" => "Meilisearch",
                "email" => "technical@cybrcrime.hu",
            ]);
        }
    }
}
