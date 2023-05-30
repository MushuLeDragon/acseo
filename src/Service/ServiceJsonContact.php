<?php

namespace App\Service;

use App\Entity\Contact;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Filesystem\Exception\IOException;

class ServiceJsonContact
{
  /**
   * CRUD JSON
   *
   * @param Contact $contact
   * @param JsonResponse $json
   * @param string $directory
   * @return IOException|boolean
   */
  public function createJson(Contact $contact, JsonResponse $json, string $directory): IOException|bool
  {
    $fs = new Filesystem;
    try {
      $fs->dumpFile($directory . $contact->getJson() . '.json', $json->getContent());
    } catch (IOException $e) {
      return $e;
    }
    return true;
  }
}
