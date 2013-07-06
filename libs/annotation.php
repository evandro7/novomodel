<?php
// Custom annotation
include 'addendum/annotations.php';
class Persistent extends Annotation {}

// Custom annotation
class Table extends Annotation {}

// Multi valued annotation
class Secured extends Annotation {
   public $role;
   public $level;
}

/** 
 * @Persistent 
 * @Table("people")
 * @Secured(role = "admin", level = 2)
 */
class Person {
   // some code
}

// getting the annotation by class name
$reflection = new ReflectionAnnotatedClass('Person');

// getting the annotation by instance
$person = new Person();
$reflection = new ReflectionAnnotatedClass($person);

// true
$reflection->hasAnnotation('Persistent'); 

// contains string "people"
echo  $reflection->getAnnotation('Table')->value; 



?>