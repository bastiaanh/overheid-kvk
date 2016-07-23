# Overheid.io KvK API client

`overheid-kvk` provides a PHP wrapper class to use the [Overheid.io KvK API](https://overheid.io/documentatie). It automatically requests additional resources when needed, so you can simply use `foreach` to walk through large result sets.

## Installation using Composer

Add the dependency:

```bash
composer require bastiaanh/overheid-kvk
```

## Known Limitations

Currently the library does not validate parameters, so make sure you validate any user input first.

Error messages returned by the webservice are also not detected. This should be improved.

## Usage

### Setup

Instantiate the class and set the API key you got from Overheid.io:

```php
$kvk = new Overheid\Kvk();
$kvk->setApiKey('459a874e2f3...');
```

### Searching through the dataset

Definition:

```
Overheid\Kvk::search([parameters : array = array()]):Overheid\Resultset
```

Example:

Specify the parameters as described [here](https://overheid.io/documentatie/kvk#list) and loop through the results.  

```
/** @var Overheid\Resultset $results */
$results = $kvk->search(array('filters' => array('postcode' => '3083cz')));

echo count($results) . ' results <br/>';
foreach ($results as $index => $result) {
    echo 'result ' . $index . ': <br />';
    var_dump($result);
}
```

Result:

```
30 results
result 0:
array (size=3)
  'dossiernummer' => string '24156072' (length=8)
  'handelsnaam' => string 'Haarzelf' (length=8)
  'subdossiernummer' => string '0002' (length=4)

result 1:
array (size=3)
  'dossiernummer' => string '24477501' (length=8)
  'handelsnaam' => string 'Anroga' (length=6)
  'subdossiernummer' => string '0000' (length=4)

// ...more...

result 28:
array (size=3)
  'dossiernummer' => string '28111270' (length=8)
  'handelsnaam' => string 'Fiscoop Holding B.V.' (length=20)
  'subdossiernummer' => string '0000' (length=4)

result 29:
array (size=3)
  'dossiernummer' => string '28111275' (length=8)
  'handelsnaam' => string 'Fiscoop Rotterdam-Zuid B.V.' (length=27)
  'subdossiernummer' => string '0000' (length=4)
```

If more than 100 results are returned, the loop will request the additional records when needed.

### Getting details about a dossier number

Definition:

```
Overheid\Kvk::get(dossierNr : string, [subDossierNr : string = '0000']):array
```

Example:
 
```php
$details = $kvk->get('20106830');
var_dump($result);
```

Result:

```php
array (size=15)
  'actief' => boolean true
  'bestaandehandelsnaam' => string 'Freshheads B.V.' (length=15)
  'dossiernummer' => string '20106830' (length=8)
  'handelsnaam' => string 'Freshheads B.V.' (length=15)
  'handelsnaam_url' => string 'freshheads-bv' (length=13)
  'huisnummer' => string '21' (length=2)
  'huisnummertoevoeging' => string '' (length=0)
  'plaats' => string 'Tilburg' (length=7)
  'postcode' => string '5041EB' (length=6)
  'statutairehandelsnaam' => string 'Freshheads B.V.' (length=15)
  'straat' => string 'Wilhelminapark' (length=14)
  'straat_url' => string 'wilhelminapark' (length=14)
  'subdossiernummer' => string '0000' (length=4)
  'type' => string 'Hoofdvestiging' (length=14)
  'vestigingsnummer' => int 18389392
```

### Getting search term suggestions

Definition:

```
Overheid\Kvk::suggest(query : string, [size : int|null = null], [fields : array|null = null]):array
```

Example:

```php
$result = $kvk->suggest('oudet', 5);
var_dump($result);
```

Result:

```php
array (size=2)
  'handelsnaam' => 
    array (size=5)
      0 => 
        array (size=2)
          'text' => string 'Oude Tijdhof Optiek' (length=19)
          'extra' => 
            array (size=1)
              'id' => string '62540661/0000' (length=13)
      1 => 
        array (size=2)
          'text' => string 'Oude Tijdhof Optiek B.V.' (length=24)
          'extra' => 
            array (size=1)
              'id' => string '05076440/0000' (length=13)
      2 => 
        array (size=2)
          'text' => string 'Oude Tol Groenprojecten' (length=23)
          'extra' => 
            array (size=1)
              'id' => string '22063560/0000' (length=13)
      3 => 
        array (size=2)
          'text' => string 'Oude Toren Beheer B.V.' (length=22)
          'extra' => 
            array (size=1)
              'id' => string '17073018/0000' (length=13)
      4 => 
        array (size=2)
          'text' => string 'Oude Toren Consultancy' (length=22)
          'extra' => 
            array (size=1)
              'id' => string '57244359/0000' (length=13)
  'straat' => 
    array (size=5)
      0 => 
        array (size=2)
          'text' => string 'OUDE TELGTERWEG' (length=15)
          'extra' => 
            array (size=1)
              'postcode' => string '3851EE' (length=6)
      1 => 
        array (size=2)
          'text' => string 'Oude Telgterweg' (length=15)
          'extra' => 
            array (size=1)
              'postcode' => string '3853PH' (length=6)
      2 => 
        array (size=2)
          'text' => string 'Oude Trambaan' (length=13)
          'extra' => 
            array (size=1)
              'postcode' => string '6093CE' (length=6)
      3 => 
        array (size=2)
          'text' => string 'OUDE TRAMBAAN' (length=13)
          'extra' => 
            array (size=1)
              'postcode' => string '2265DA' (length=6)
      4 => 
        array (size=2)
          'text' => string 'OUDE TERBORGSEWEG' (length=17)
          'extra' => 
            array (size=1)
              'postcode' => string '7004KA' (length=6)
```
