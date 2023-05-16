<!-- CLASES -->
<?php
require 'db.php';

class READ
{
  public function datos($tSQL, $parameters)
  {
    $db = Database::connect();

    $date = date('Y-m-d H:i:s');
    $sql = 'CALL '.$tSQL.'('.$parameters.')';
    $statement = $db->prepare($sql);

    if ($parameters == ':date')
    {
      $statement->bindParam(':date', $date, PDO::PARAM_STR);
    }

    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC); 

    Database::disconnect();

    return $result;
  }
}

?>