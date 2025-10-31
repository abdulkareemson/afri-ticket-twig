<?php
class Storage {
  private static $basePath = __DIR__ . '/../data/';

  /**
   * Get data from a JSON file.
   */
  public static function get(string $fileName): array {
    $path = self::$basePath . $fileName . '.json';
    if (!file_exists($path)) return [];

    $json = file_get_contents($path);
    $data = json_decode($json, true);
    return is_array($data) ? $data : [];
  }

  /**
   * Save data to a JSON file.
   */
  public static function set(string $fileName, array $data): bool {
    $path = self::$basePath . $fileName . '.json';
    $json = json_encode($data, JSON_PRETTY_PRINT);
    return file_put_contents($path, $json) !== false;
  }

  /**
   * Find a record in JSON by matching key-value pairs.
   */
  public static function find(string $fileName, array $criteria): ?array {
    $records = self::get($fileName);
    foreach ($records as $record) {
      $match = true;
      foreach ($criteria as $key => $value) {
        if (!isset($record[$key]) || $record[$key] != $value) {
          $match = false;
          break;
        }
      }
      if ($match) return $record;
    }
    return null;
  }

  /**
   * Add a new record to JSON storage.
   */
  public static function add(string $fileName, array $record): bool {
    $records = self::get($fileName);
    $records[] = $record;
    return self::set($fileName, $records);
  }

  /**
   * Update a record (by key-value pair match).
   */
  public static function update(string $fileName, array $criteria, array $newData): bool {
    $records = self::get($fileName);
    $updated = false;

    foreach ($records as &$record) {
      $match = true;
      foreach ($criteria as $key => $value) {
        if (!isset($record[$key]) || $record[$key] != $value) {
          $match = false;
          break;
        }
      }

      if ($match) {
        $record = array_merge($record, $newData);
        $updated = true;
        break;
      }
    }

    if ($updated) {
      return self::set($fileName, $records);
    }
    return false;
  }

  /**
   * Delete a record by criteria.
   */
  public static function delete(string $fileName, array $criteria): bool {
    $records = self::get($fileName);
    $filtered = array_filter($records, function ($record) use ($criteria) {
      foreach ($criteria as $key => $value) {
        if (isset($record[$key]) && $record[$key] == $value) {
          return false; // remove
        }
      }
      return true;
    });

    return self::set($fileName, array_values($filtered));
  }
}
