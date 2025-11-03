<?php

namespace App\Entity;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Book.
 */
class BookTest extends TestCase
{
    public function testSetAndGetTitle(): void
    {
        $book = new Book();
        $book->setTitle("Hunger Games");
        $this->assertEquals("Hunger Games", $book->getTitle());
    }

    public function testSetAndGetIsbn(): void
    {
        $book = new Book();
        $book->setIsbn("12345678");
        $this->assertEquals("12345678", $book->getIsbn());
    }

    public function testSetAndGetAuthor(): void
    {
        $book = new Book();
        $book->setAuthor("Suzanne Collins");
        $this->assertEquals("Suzanne Collins", $book->getAuthor());
    }

    public function testSetAndGetImage(): void
    {
        $book = new Book();
        $book->setImage("bookcover.jpg");
        $this->assertEquals("bookcover.jpg", $book->getImage());
    }
}
