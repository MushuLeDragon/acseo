<?php

namespace App\Service;

class ServiceJsonContact
{
  /**
   * CRUD JSON
   *
   * @param string $email
   * @param array $datas
   * @param string $directory
   * @return integer|boolean
   */
  public function createJson(string $email, array $datas, string $directory): int|bool
  {
    $jsonData = json_encode([$datas['email'] => [$datas]], JSON_PRETTY_PRINT);
    $filePath = $directory . $email . '.json';

    if (file_exists($filePath)) {
      $check = $this->checkJson($email, $datas, $filePath);
      // dd($check);
      if ($check) {
        return false; // Json already exists, do nothing then return false
      } else {
        // New message from contat, lets add it to JSON
        $jsonData = $this->pushJson($email, $datas, $filePath);
      }
    }

    return file_put_contents($filePath, $jsonData);
  }

  /**
   * Add new message to contact JSON
   *
   * @param string $email
   * @param array $datas
   * @param string $filePath
   * @return string
   */
  private function pushJson(string $email, array $datas, string $filePath): string
  {
      $str = file_get_contents($filePath);
      $json = json_decode($str, true);
      $json[$email][] = $datas;

      return json_encode($json, JSON_PRETTY_PRINT);
  }

  /**
   * Return false if JSON does not exists
   * Return false if contact message does not exists
   * Return true if contact message already exists
   *
   * @param string $email
   * @param array $datas
   * @param string $filePath
   * @return boolean
   */
  private function checkJson(string $email, array $datas, string $filePath): bool
  {
    if (file_exists($filePath)) {
      $str = file_get_contents($filePath);
      $json = json_decode($str, true);

      $search = array_search($datas['message'], array_column($json[$email], 'message'));
      // dd($search);

      if ($search === false) {
        return false;
      }
      return true;
    }
    return false;
  }
}
