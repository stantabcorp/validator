<?php

use PHPUnit\Framework\TestCase;
use Stantabcorp\Validator\CustomValidator;
use Stantabcorp\Validator\Validator;

class SimpleTest extends TestCase
{

    public function testConstructor()
    {
        $validator = $this->createValidator([]); // Empty array, everything should fail
        $validator->notNull("abc");
        $this->assertCount(1, $validator->getErrors());

        $validator = $this->createValidator(NULL); // Null body, everything should fail
        $validator->notNull("abc");
        $this->assertCount(1, $validator->getErrors());
    }

    public function testNotNull()
    {
        $validator = $this->createValidator(["abc" => "abc"]);
        $validator->notNull("abc");
        $this->assertCount(0, $validator->getErrors());

        $validator = $this->createValidator(["abc" => NULL]);
        $validator->notNull("abc");
        $this->assertCount(1, $validator->getErrors());
    }

    private function createValidator(?array $body): Validator
    {
        return new Stantabcorp\Validator\Validator($body);
    }

    public function testNotEmpty()
    {
        $validator = $this->createValidator(["abc" => "abc"]);
        $validator->notEmpty("abc");
        $this->assertCount(0, $validator->getErrors(), json_encode($validator->getErrors()));

        $validator = $this->createValidator(["abc" => ""]);
        $validator->notEmpty("abc");
        $this->assertCount(1, $validator->getErrors(), json_encode($validator->getErrors()));
    }

    public function testRequired()
    {
        $validator = $this->createValidator(["abc" => "abc"]);
        $validator->required("abc");
        $this->assertCount(0, $validator->getErrors(), json_encode($validator->getErrors()));

        $validator = $this->createValidator(["def" => ""]);
        $validator->required("abc");
        $this->assertCount(1, $validator->getErrors(), json_encode($validator->getErrors()));
    }

    public function testLength()
    {
        $validator = $this->createValidator(["abc" => "abc"]);
        $validator->length("abc", 2, 4);
        $this->assertCount(0, $validator->getErrors(), json_encode($validator->getErrors()));

        $validator = $this->createValidator(["abc" => "abcdefghi"]);
        $validator->length("abc", 2, 4);
        $this->assertCount(1, $validator->getErrors(), json_encode($validator->getErrors()));
    }

    public function testLengthArray()
    {
        $validator = $this->createValidator(["abc" => ["a", "b", "c"]]);
        $validator->length("abc", 2, 4);
        $this->assertCount(0, $validator->getErrors(), json_encode($validator->getErrors()));

        $validator = $this->createValidator(["abc" => ["a", "b", "c", "d", "e", "f"]]);
        $validator->length("abc", 2, 4);
        $this->assertCount(1, $validator->getErrors(), json_encode($validator->getErrors()));
    }

    public function testDateTime()
    {
        $validator = $this->createValidator(["abc" => "2022-03-20 03:08:42"]);
        $validator->dateTime("abc");
        $this->assertCount(0, $validator->getErrors(), json_encode($validator->getErrors()));

        $validator = $this->createValidator(["abc" => "2022-03-22"]);
        $validator->dateTime("abc", "Y-m-d");
        $this->assertCount(0, $validator->getErrors(), json_encode($validator->getErrors()));

        $validator = $this->createValidator(["abc" => "ABCD-EF-GH IJ:KL:MN"]);
        $validator->dateTime("abc");
        $this->assertCount(1, $validator->getErrors(), json_encode($validator->getErrors()));
    }

    public function testSlug()
    {
        $validator = $this->createValidator(["abc" => "abc-def"]);
        $validator->slug("abc");
        $this->assertCount(0, $validator->getErrors(), json_encode($validator->getErrors()));

        $validator = $this->createValidator(["abc" => "abc def"]);
        $validator->slug("abc");
        $this->assertCount(1, $validator->getErrors(), json_encode($validator->getErrors()));
    }

    public function testUrl()
    {
        $validator = $this->createValidator(["abc" => "https://stantabcorp.com"]);
        $validator->url("abc");
        $this->assertCount(0, $validator->getErrors(), json_encode($validator->getErrors()));

        $validator = $this->createValidator(["abc" => "stantabcorp.com"]);
        $validator->url("abc");
        $this->assertCount(1, $validator->getErrors(), json_encode($validator->getErrors()));
    }

    public function testMatch()
    {
        $validator = $this->createValidator(["abc" => "def"]);
        $validator->match("abc", "def");
        $this->assertCount(0, $validator->getErrors(), json_encode($validator->getErrors()));

        $validator = $this->createValidator(["abc" => "hij"]);
        $validator->match("abc", "def");
        $this->assertCount(1, $validator->getErrors(), json_encode($validator->getErrors()));
    }

    public function testEqual()
    {
        $validator = $this->createValidator(["abc" => "abc", "def" => "abc"]);
        $validator->equal("abc", "def");
        $this->assertCount(0, $validator->getErrors(), json_encode($validator->getErrors()));

        $validator = $this->createValidator(["abc" => "", "def" => "abc"]);
        $validator->equal("abc", "def");
        $this->assertCount(1, $validator->getErrors(), json_encode($validator->getErrors()));
    }

    public function testEmail()
    {
        $validator = $this->createValidator(["abc" => "noreply@stantabcorp.com"]);
        $validator->email("abc");
        $this->assertCount(0, $validator->getErrors(), json_encode($validator->getErrors()));

        $validator = $this->createValidator(["abc" => "not an email"]);
        $validator->email("abc");
        $this->assertCount(1, $validator->getErrors(), json_encode($validator->getErrors()));
    }

    public function testArray()
    {
        $validator = $this->createValidator(["abc" => ["some" => "content"]]);
        $validator->array("abc");
        $this->assertCount(0, $validator->getErrors(), json_encode($validator->getErrors()));

        $validator = $this->createValidator(["abc" => "not an array"]);
        $validator->array("abc");
        $this->assertCount(1, $validator->getErrors(), json_encode($validator->getErrors()));
    }

    public function testInteger()
    {
        $validator = $this->createValidator(["abc" => "1"]);
        $validator->integer("abc");
        $this->assertCount(0, $validator->getErrors(), json_encode($validator->getErrors()));

        $validator = $this->createValidator(["abc" => "1,1"]);
        $validator->integer("abc");
        $this->assertCount(1, $validator->getErrors(), json_encode($validator->getErrors()));
    }

    public function testFloat()
    {
        $validator = $this->createValidator(["abc" => "2.00"]);
        $validator->float("abc");
        $this->assertCount(0, $validator->getErrors(), json_encode($validator->getErrors()));

        $validator = $this->createValidator(["abc" => "not a float"]);
        $validator->float("abc");
        $this->assertCount(1, $validator->getErrors(), json_encode($validator->getErrors()));
    }

    public function testBoolean()
    {
        $validator = $this->createValidator(["abc" => true]);
        $validator->boolean("abc");
        $this->assertCount(0, $validator->getErrors(), json_encode($validator->getErrors()));

        $validator = $this->createValidator(["abc" => "not a boolean"]);
        $validator->boolean("abc");
        $this->assertCount(1, $validator->getErrors(), json_encode($validator->getErrors()));
    }

    public function testBetween()
    {
        $validator = $this->createValidator(["abc" => 42]);
        $validator->between("abc", 21, 69);
        $this->assertCount(0, $validator->getErrors(), json_encode($validator->getErrors()));

        $validator = $this->createValidator(["abc" => 9999]);
        $validator->between("abc", 21, 69);
        $this->assertCount(1, $validator->getErrors(), json_encode($validator->getErrors()));
    }

    public function testAlphaNumerical()
    {
        $validator = $this->createValidator(["abc" => "abc098"]);
        $validator->alphaNumerical("abc");
        $this->assertCount(0, $validator->getErrors(), json_encode($validator->getErrors()));

        $validator = $this->createValidator(["abc" => " abc-def"]);
        $validator->email("abc");
        $this->assertCount(1, $validator->getErrors(), json_encode($validator->getErrors()));
    }

    public function testCustomValidation()
    {
        $validator = $this->createValidator(["abc" => "random"]);
        $validator->customValidation("abc", function (CustomValidator $customValidator) {

        });
        $this->assertCount(0, $validator->getErrors(), json_encode($validator->getErrors()));

        $validator = $this->createValidator(["abc" => "random"]);
        $validator->customValidation("abc", function (CustomValidator $customValidator) {
            $customValidator->addError("random");
        });
        $this->assertCount(1, $validator->getErrors(), json_encode($validator->getErrors()));
        $this->assertEquals("random", $validator->getErrors()[0], json_encode($validator->getErrors()));
    }

    public function testList()
    {
        $validator = $this->createValidator(["abc" => ["abc", "def"]]);
        $validator->list("abc");
        $this->assertCount(0, $validator->getErrors(), json_encode($validator->getErrors()));

        $validator = $this->createValidator(["abc" => ["abc" => "def", "foo" => "bar"]]);
        $validator->list("abc");
        $this->assertCount(1, $validator->getErrors(), json_encode($validator->getErrors()));

        $validator = $this->createValidator(["abc" => "str"]);
        $validator->list("abc");
        $this->assertCount(1, $validator->getErrors(), json_encode($validator->getErrors()));
    }

}