<?php

namespace App\Helper;

use App\Entity\Champion;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class ImporterHelper 
{
    const DATA_URL = 'https://ddragon.leagueoflegends.com/cdn/13.1.1/data/fr_FR/champion.json';
    const DETAIL_URL = 'https://ddragon.leagueoflegends.com/cdn/13.1.1/data/fr_FR/champion/__CHAMPID__.json';
    const BANNER_URL = 'https://ddragon.leagueoflegends.com/cdn/img/champion/splash/__CHAMPID___0.jpg';

    private EntityManager $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    public function importOrUpdateLolChampions(): int
    {
        $totalImported = 0;

        $champions = $this->getLolData(self::DATA_URL);

        if ($champions === null) {
            throw new \Exception("Champions not found", 1);
        }

        foreach ($champions as $_champion) {
            $extraData = $this->getLolData(str_replace('__CHAMPID__', $_champion['id'], self::DETAIL_URL));

            if($extraData === null) continue;

            $champion = $this->em->getRepository(Champion::class)->findOneBy(['lolId' => $_champion['id']]);

            if ($champion === null) $champion = new Champion();

            $champion->setName($_champion['name']);
            $champion->setTitle($_champion['title']);
            $champion->setLolId($_champion['id']);
            $champion->setLolKey($_champion['key']);
            $champion->setLore($extraData[$_champion['id']]['lore']);
            $champion->setBanner(str_replace('__CHAMPID__',$_champion['name'] ,self::BANNER_URL));

            $this->em->persist($champion);

            $totalImported++;
        }

        $this->em->flush();

        return $totalImported;
    }
    private function getLolData(string $url): ?array
    {
        $ch = curl_init($url);
        
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $data = curl_exec($ch);

        curl_close($ch);

        return json_decode($data, true)['data'] ?? null;
    }
}