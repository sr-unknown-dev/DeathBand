<?php

namespace Ghost\claims;

use Ghost\Loader;
use pocketmine\utils\Config;

class ClaimManager {

    private $claims = [];
    private $config;

    public function __construct() {
        $this->config = new Config(Loader::getInstance()->getDataFolder() . "claims.yml", Config::YAML);
        $this->loadClaims();
    }

    public function createClaim($claim, $x1, $z1, $x2, $z2) {
        $this->claims[] = [
            'claim' => $claim,
            'x1' => $x1,
            'z1' => $z1,
            'x2' => $x2,
            'z2' => $z2
        ];
        $this->saveClaims();
    }

    public function deleteClaim($claim) {
        foreach ($this->claims as $key => $claim) {
            if ($claim['claim'] === $claim) {
                unset($this->claims[$key]);
            }
        }
        $this->saveClaims();
    }

    public function isClaimed($x, $z) {
        foreach ($this->claims as $claim) {
            if ($x >= $claim['x1'] && $x <= $claim['x2'] && $z >= $claim['z1'] && $z <= $claim['z2']) {
                return true;
            }
        }
        return false;
    }

    public function getClaim($x, $z) {
        foreach ($this->claims as $claim) {
            if ($x >= $claim['x1'] && $x <= $claim['x2'] && $z >= $claim['z1'] && $z <= $claim['z2']) {
                return $claim;
            }
        }
        return null;
    }

    private function saveClaims() {
        $this->config->setAll($this->claims);
        $this->config->save();
    }

    private function loadClaims() {
        $this->claims = $this->config->getAll();
    }

    public function claimNotExists($claim) {
        foreach ($this->claims as $existingClaim) {
            if ($existingClaim['claim'] === $claim) {
                return true;
            }
        }
        return false;
    }

    public function claimExists($claim) {
        foreach ($this->claims as $existingClaim) {
            if ($existingClaim['claim'] === $claim) {
                return true;
            }
        }
        return false;
    }
}
?>