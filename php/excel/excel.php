<?php
//name = [имя, расширение, путь]
function w_w_excel($name,$data,$headers,$styles,$download = false) {
	$document = new \PHPExcel();
    $document->getProperties()->setCreator("Saiash");
    $document->getProperties()->setLastModifiedBy("Saiash");
    $document->getProperties()->setTitle($name[0]);
    $document->getProperties()->setSubject($name[0]);
    $document->getProperties()->setDescription($name[0]);
    $document->setActiveSheetIndex(0);
    
    $final_cell = $this->set_data($document,$data,$headers);
    $this->set_styles($document,$styles,$final_cell);

    $objWriter = new \PHPExcel_Writer_Excel2007($document);
    $objWriter->save($name[2].$name[0].'.'.$name[1]);

    if ($download == true) {
    	$file = $name[2].$name[0].'.'.$name[1]; 
        if(file_exists($file)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename='.basename($file));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            ob_clean();
            flush();
            readfile($file);
            exit;
        }
    }
}

function set_data($document,$data,$headers) {
	$row = 0;
	$document_AS = $document->getActiveSheet();
	if (!empty($headers)) {
		$row++;
		$column = 'A';
		foreach ($headers as $key => $value) {
	        $document_AS->SetCellValue($column.$row, $value);
	        $column++;
	    }
	}
	if (!empty($data)) {
		foreach ($data as $row_key => $row_value) {
            $row++;
            $column = 'A';
            foreach ($row_value as $column_key => $column_value) {
                $document_AS->SetCellValue($column.$row, $column_value);
                $column++;
            }
        }
	}
	return [--$column,$row];
}

// стили задаются в формате [первая ячейка, последняя ячейка, [стиль]]
// Если вместо стиля указано ключевое слово "AutoSize", то последняя ячейка = null, в первой ячейке перечислены столбцы
// Если вместо стиля указано ключевое слово "AutoFilter", то для указанных ячеек будут применены фильтры
// Если вместо последней ячейки указано ключевое слово "all", в нее будет подставлено значение последней заполненной ячейки
// Если вместо последней ячейки указано ключевое слово "odd", стили будут чередоваться через один, стилей должно быть два.
function set_styles($document,$styles,$final_cell) {
	$document_AS = $document->getActiveSheet();
	foreach ($styles as $style_num => $style_values) {
		if ($style_values[1] == 'all') {
			$style_values[1] = $final_cell[0].$final_cell[1];
		}
		if (is_array($style_values[2])) {
			if ($style_values[1] != 'odd') {
				$document_AS->getStyle($style_values[0].':'.$style_values[1])->applyFromArray($style_values[2]);
			} else {
				$i = $style_values[0][1];
				while ($i < $final_cell[1]) {
		            $target = $style_values[0][0].$i.':'.$final_cell[0].$i;
		            if ($d%2==1) {
		                $document_AS->getStyle($target)->applyFromArray($style_values[2][0]);
		            } else {
		                $document_AS->getStyle($target)->applyFromArray($style_values[2][1]);
		            }
		            $d++;           
		        }
			}
		} else {
			switch ($style_values[2]) {
				case 'AutoSize':
					foreach ($style_values[0] as $key => $column_symbol) {
						$document_AS->getColumnDimension($column_symbol)->setAutoSize(true);
					}
					break;
				
				case 'AutoFilter':
					$document_AS->setAutoFilter($style_values[0].':'.$style_values[1]);
					break;
			}
		}
	}
}