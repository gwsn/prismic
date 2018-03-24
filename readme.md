## Prismic.io Wrapper

## Installation

Require this package
```
composer require gwsn/prismic
```

## Frameworks specific packages
1. [Lumen/Laravel](https://github.com/gwsn/lumen-transformer)
`composer require gwsn/lumen-transformer`
1. [Symfony](https://github.com/gwsn/symfony-transformer)
`composer require gwsn/symfony-transformer`


## Simple example
Include the Transformer class:

`use Gwsn\Prismic\Transformer;`

In this use case we work only with arrays.
```
$source = [
    [
        'id'   => '1',
        'name' => 'Test 1',
        'type' => 'text',
        'text' => 'Some text written by Cicero that's used to fill spaces on.'
    ],
    [
        'id'   => '2',
        'name' => 'Test 2',
        'type' => 'short-text',
        'text' => 'The reason why it is difficult to understand is to draw attention away from the words on a page and to focus it on the design instead.'
    ],
    [
        'id'   => '3',
        'name' => 'Test 3',
        'type' => 'text',
        'text' => 'It's also useful for filling spaces where text should be because its words are about the same length as normal English writing.'
    ],
];

$mapping = [
    'guid' => 'id',
    'slug' => 'name',
    'type' => 'type',
    'text' => 'text',
];

$target = Transformer::run($source, Transformer::BuildMapping($mapping));
 
var_dump(json_encode($target)); 
output:
 
 [
     {
         guid: "1",
         slug: "Test Company mapper 1",
         type: "company",
         text: "asdasdfafa afad afads aadssafdf astext"
     },
     {
         guid: "2",
         slug: "Test Company mapper 2",
         type: "blog",
         text: "asdasdfafa afad afads aadssafdf astext"
     },
     {
         guid: "3",
         slug: "Test Company mapper 3",
         type: "company",
         text: "asdasdfafa afad afads aadssafdf astext"
     }
 ]
```

For the more advanced documentation about the usage you can find it here
[documentation](https://github.com/gwsn/Transformer/tree/master/examples/usage.md)