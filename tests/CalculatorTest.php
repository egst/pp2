<?php declare(strict_types = 1);

namespace IW\Tests\Workshop;

use \Exception;
use \InvalidArgumentException;

use \PHPUnit\Framework\TestCase;

use \IW\Workshop\Calculator;

/**
 *  @covers Calculator
 *  @psalm-suppress PropertyNotSetInConstructor runTestInSeparateProcess, backupStaticAttributes from TestCase
 */
final class CalculatorTest extends TestCase {

    private const delta = 0.001;

    /** @return list<array{float, float, float}> */
    public function additionData (): array {
        return [
            [ 1.5,  5.5,  7.],
            [-1.5, -5.5, -7.],
            [-1.5,  1.5,  0.],
        ];
    }

    /** @return list<array{float, float, float}> */
    public function divisionData (): array {
        return [
            [ 5.5,  2., 2.75],
            [ 2.,   2., 1.],
            [ 0.,   3., 0.],
            // Positive and negative infinities are not tested.
            [ 2.,  INF, 0.],
            [-2.,  INF, 0.],
            [ 2., -INF, 0.],
            [-2., -INF, 0.],
            [ 0.,  INF, 0.],
            [-0.,  INF, 0.],
        ];
    }

    /** @return list<array{float}> */
    public function divisionByZeroData (): array {
        return [
            [ 0.],
            [ 2.],
            [-2.],
        ];
    }

    /** @return list<array{float, float, int}> */
    public function additionInfinityData (): array {
        return [
            [INF,   INF, +1],
            [-INF, -INF, -1],
        ];
    }

    /** @return list<array{float, float, int}> */
    public function divisionInfinityData (): array {
        return [
            [ INF,  2., +1],
            [ INF, -2., -1],
            [-INF,  2., -1],
            [-INF, -2., +1],
        ];
    }

    /** @return list<array{float, float}> */
    public function additionNanData (): array {
        return [
            [ INF, -INF],
            [-INF,  INF],
            [ NAN,  NAN],
        ];
    }

    /** @return list<array{float, float}> */
    public function divisionNanData (): array {
        return [
            [ INF,  INF],
            [ INF, -INF],
            [-INF,  INF],
            [-INF, -INF],
            [ NAN,  NAN],
        ];
    }

    /** 
     *  @dataProvider additionData
     *  @throws Exception
     */
    public function testAddition (float $a, float $b, float $expected): void {
        $this->assertEqualsWithDelta($expected, (new Calculator)->add($a, $b), self::delta);
    }

    /** 
     *  @dataProvider divisionData
     *  @throws Exception
     */
    public function testDivision (float $a, float $b, float $expected): void {
        $this->assertEqualsWithDelta($expected, (new Calculator)->divide($a, $b), self::delta);
    }

    /** 
     *  @dataProvider divisionByZeroData
     */
    public function testDivisionByZero (float $a): void {
        $this->expectException(InvalidArgumentException::class);
        (new Calculator)->divide($a, 0);
    }

    /** 
     *  @dataProvider additionInfinityData
     *  @throws Exception
     */
    public function testAdditionInfinity (float $a, float $b, int $expectedSign): void {
        $this->assertSignedInfinity((new Calculator)->add($a, $b), $expectedSign);
    }

    /** 
     *  @dataProvider divisionInfinityData
     *  @throws Exception
     */
    public function testDivisionInfinity (float $a, float $b, int $expectedSign): void {
        $this->assertSignedInfinity((new Calculator)->divide($a, $b), $expectedSign);
    }

    /** 
     *  @dataProvider additionNanData
     *  @throws Exception
     */
    public function testAdditionNan (float $a, float $b): void {
        $this->assertNan((new Calculator)->add($a, $b));
    }

    /** 
     *  @dataProvider divisionNanData
     *  @throws Exception
     */
    public function testDivisionNan (float $a, float $b): void {
        $this->assertNan((new Calculator)->divide($a, $b));
    }

    /** @throws Exception */
    private function assertSignedInfinity (float $result, int $expectedSign): void {
        $this->assertInfinite($result);
        if ($expectedSign > 0)
            $this->assertGreaterThan(0, $result);
        else
            $this->assertLessThan(0, $result);
    }

}
