<?php

namespace App\Exports;

use App\DataForm;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Sheet;
use function foo\func;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;


ini_set('memory_limit', '-1');
set_time_limit(0);
ini_set('max_execution_time', 0);


class LabelBox implements
    FromCollection,
    ShouldAutoSize,
    WithHeadings,
    WithTitle,
    WithEvents,
    WithStrictNullComparison//,
   // WithDrawings
    //Coordinate,
    //DataValidation
{

    //private $lastRow;
    private static $ALIGNMENT = '\\PhpOffice\\PhpSpreadsheet\\Style\\Alignment';
    private static $FILL = '\\PhpOffice\\PhpSpreadsheet\\Style\\Fill';
    private static $BORDER = '\\PhpOffice\\PhpSpreadsheet\\Style\\Border';

    //public function __construct($headers, $fields)
    public function __construct($headers, $fields)
    {
        $this->headers = $headers;
        $this->fields = $fields;
    }

    public function headings(): array
    {

        return $this->headers;
    }

    public function title(): string
    {
        return 'Etiqueta de expediente';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $event->sheet->setShowGridlines(false);

                $event->sheet->getColumnDimension('B')->setWidth(2.00);
                $event->sheet->getColumnDimension('C')->setWidth(15.00);
                $event->sheet->getColumnDimension('D')->setWidth(15.00);
                $event->sheet->getColumnDimension('F')->setWidth(15.00);
                $event->sheet->getColumnDimension('F')->setWidth(15.00);
                $event->sheet->getColumnDimension('G')->setWidth(2.00);

                $event->sheet->rowHeight('6', 60);
                $event->sheet->rowHeight('4', 50);
                $event->sheet->rowHeight('5', 50);

                $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                $drawing->setName('Logo');
                $drawing->setDescription('Logo');

                $drawing->setResizeProportional(false);
                $drawing->setWidth(379);
                $drawing->setHeight(49);
                $drawing->setOffsetX(13);
                $drawing->setOffsetY(7);
                $drawing->setPath(public_path('/img/RelacionesExterioresExcelEtqueta.png'));
                $drawing->setCoordinates('B3');
                $drawing->setWorksheet($event->sheet->getDelegate());


                $event->sheet->mergeCells( 'B2:G2' );
                $event->sheet->mergeCells( 'B3:G3' );
                $event->sheet->rowHeight('3', 50);
                $event->sheet->setCellValue('B4','Unidad Administrativa');
                $event->sheet->mergeCells( 'B4:G4' );

                $event->sheet->rowHeight('5', 30);
                $event->sheet->mergeCells( 'B5:G5' );

                $data = [
                    [ 'cell'=>'B2:G2', 'border'=> 'top', 'typeBorder'=> static::$BORDER::BORDER_DOUBLE],
                    [ 'cell'=>'B25:G25', 'border'=> 'bottom','typeBorder'=> static::$BORDER::BORDER_DOUBLE],
                    [ 'cell'=>'B2:B25', 'border'=> 'left','typeBorder'=> static::$BORDER::BORDER_DOUBLE],
                    [ 'cell'=>'G2:G25', 'border'=> 'right','typeBorder'=> static::$BORDER::BORDER_DOUBLE],

                    [ 'cell'=>'D14:D14', 'border'=> 'bottom','typeBorder'=> static::$BORDER::BORDER_THIN],
                    [ 'cell'=>'F14:F14', 'border'=> 'bottom','typeBorder'=> static::$BORDER::BORDER_THIN],

                    [ 'cell'=>'D16:F16', 'border'=> 'bottom','typeBorder'=> static::$BORDER::BORDER_THIN],
                    [ 'cell'=>'C22:F22', 'border'=> 'bottom','typeBorder'=> static::$BORDER::BORDER_THIN],
                ];

                foreach ($data as $i) {
                    $event->sheet->styleCells(
                        $i['cell'],
                        [
                            'alignment' => [
                                'horizontal' => static::$ALIGNMENT::HORIZONTAL_CENTER,
                                'vertical' => static::$ALIGNMENT::VERTICAL_CENTER,
                            ],
                            'borders' => [
                                $i['border'] => [
                                    'borderStyle' => $i['typeBorder'],
                                    'color' => ['argb' => 'FF000000'],
                                ]
                            ]
                        ]
                    );
                }

                $strlen = strlen($this->fields['unidad_admin']) / 13;
                $fontSize = (  $strlen > 12 )? $strlen : 12;

                $data = [
                    [ 'cell'=>'B4:G4',   'bold'=> true, 'fontSize'=> $fontSize,  'field' => $this->fields['unidad_admin']],
                    [ 'cell'=>'B5:G5',   'bold'=> true, 'fontSize'=> 26,  'field' => ''],
                    [ 'cell'=>'C6',      'bold'=> true, 'fontSize'=> 14,  'field' => 'Fondo:'],
                    [ 'cell'=>'D6:F6',   'bold'=> true, 'fontSize'=> 18,  'field' => 'SRE Secretar??a de Relaciones Exteriores.'],
                    [ 'cell'=>'C7',      'bold'=> true, 'fontSize'=> 14,  'field' => 'Secci??n:'],
                    [ 'cell'=>'D7:F7',   'bold'=> true, 'fontSize'=> 14,  'field' => ''],
                    [ 'cell'=>'C8',      'bold'=> true, 'fontSize'=> 14,  'field' => 'Serie:'],
                    [ 'cell'=>'D8:F8',   'bold'=> true, 'fontSize'=> 14,  'field' => ''],
                    [ 'cell'=>'C9',      'bold'=> true, 'fontSize'=> 14,  'field' => 'Subserie:'],
                    [ 'cell'=>'D9:F9',   'bold'=> true, 'fontSize'=> 14,  'field' => ''],
                    [ 'cell'=>'B11:G11', 'bold'=> true, 'fontSize'=> 34,  'field' => ''],
                    [ 'cell'=>'B12:G12', 'bold'=> true, 'fontSize'=> 20,  'field' => 'Expedientes'],
                    [ 'cell'=>'C14',     'bold'=> true, 'fontSize'=> 20,  'field' => 'Del:'],
                    [ 'cell'=>'E14',     'bold'=> true, 'fontSize'=> 20,  'field' => 'Al:'],
                    [ 'cell'=>'C16',     'bold'=> true, 'fontSize'=> 20,  'field' => 'Perido'],
                    [ 'cell'=>'C18:F18', 'bold'=> true, 'fontSize'=> 18,  'field' => 'Caja'],
                    [ 'cell'=>'C19:F19', 'bold'=> true, 'fontSize'=> 60,  'field' => ''],
                    [ 'cell'=>'C21:F21', 'bold'=> true, 'fontSize'=> 16,  'field' => 'No. de transferencia'],
                ];
                foreach ($data as $i) {
                    $cel = preg_split("/[:]+/", $i['cell']);
                    $event->sheet->setCellValue($cel[0],$i['field']);
                    $event->sheet->wrapText( $i['cell'] );
                    if( count($cel) === 2 ){
                        $event->sheet->mergeCells( $i['cell'] );
                    }
                    $event->sheet->styleCells(
                        $i['cell'],
                        [
                            'font' => [
                                'bold' => $i['bold'],
                                'name' => 'Calibri',
                                'size' => $i['fontSize']
                            ],
                            'alignment' => [
                                'horizontal' => static::$ALIGNMENT::HORIZONTAL_CENTER,
                                'vertical' => static::$ALIGNMENT::VERTICAL_CENTER,
                            ]
                        ]
                    );

                }


            },
        ];
    }

    public function collection()
    {
        try {

            return collect();

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'No se pudo completar la acci??n' . $e,
                'errror' => $e->getMessage()
            ], 500);
        }
    }
}
