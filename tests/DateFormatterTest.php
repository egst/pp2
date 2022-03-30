<?php declare(strict_types = 1);

namespace IW\Tests\Workshop;

use \DateTime;
use \Exception;

use \PHPUnit\Framework\TestCase;

use \IW\Workshop\DateFormatter;

/**
 *  @covers DateFormatter
 *  @psalm-suppress PropertyNotSetInConstructor runTestInSeparateProcess, backupStaticAttributes from TestCase
 */
final class DateFormatterTest extends TestCase {

    /** @return list<array{DateTime, 'Night' | 'Morning' | 'Afternoon' | 'Evening'}> */
    public function partOfDayData (): array {
        return [
            [new DateTime('00:00'), 'Night'],
            [new DateTime('01:00'), 'Night'],
            [new DateTime('05:59'), 'Night'],
            [new DateTime('06:00'), 'Morning'],
            [new DateTime('07:00'), 'Morning'],
            [new DateTime('11:59'), 'Morning'],
            [new DateTime('12:00'), 'Afternoon'],
            [new DateTime('13:00'), 'Afternoon'],
            [new DateTime('17:59'), 'Afternoon'],
            [new DateTime('18:00'), 'Evening'],
            [new DateTime('19:00'), 'Evening'],
            [new DateTime('23:59'), 'Evening'],
        ];
    }

    /** 
     *  @dataProvider partOfDayData
     *  @param 'Night' | 'Morning' | 'Afternoon' | 'Evening' $expected
     *  @throws Exception
     */
    public function testPartOfDay (DateTime $dateTime, string $expected): void {
        $this->assertEquals($expected, (new DateFormatter)->getPartOfDay($dateTime));
    }


}
