<html>
  <head>
    <title>Weather</title>
    <link rel="stylesheet" href="../reset.css">
    <link rel="stylesheet" href="../standard.css">
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
  </head>
  <body class="centered vertical">
    <h1>Wetter</h1>
    <?php
      // Die benötigten Dateien werden eingebunden
      require_once 'vendor/autoload.php';
      use GuzzleHttp\Client;

      // Die URL der API und der API-Key
      $url = 'https://api.openweathermap.org/data/2.5/weather';
      $apiKey = '<API Key>';

      // Ein HTTP-Client wird erstellt
      $client = new Client();

      // Die Anfrage an die API
      $response = $client->request('GET', $url, [
        'query' => [
          'q' => $_POST['city'],
          'appid' => $apiKey,
          'units' => 'metric',
          'lang' => 'de'
        ]
      ]);

      // Die Antwort der API
      $data = json_decode($response->getBody(), true);

      // Die Wetterdaten
      $city = $data['name'];
      $temp = $data['main']['temp'];
      $humidity = $data['main']['humidity'];
      $windSpeed = $data['wind']['speed'];
      $description = $data['weather'][0]['description'];
      $icon = $data['weather'][0]['icon'];

      // Ausgabe der Wetterdaten
      echo "<p>Die Temperatur in $city beträgt $temp °C.</p>";
      echo "<p>Die Luftfeuchtigkeit beträgt $humidity %.</p>";
      echo "<p>Die Windgeschwindigkeit beträgt $windSpeed km/h.</p>";
      echo "<p>Die Wetterbeschreibung lautet: $description.</p>";
      echo "<img width='100' height='100' src='https://openweathermap.org/img/w/$icon.png'>";
    ?>
    </form>
  </body>
</html>