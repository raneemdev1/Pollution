<?php
/**
 * استيراد بيانات التلوث من ملف CSV المُصدَّر من Pollution_data.ipynb
 * استدعاء هذا الملف بعد تشغيل الدفتر وتصدير pollution_export_for_db.csv
 */
session_start();
require_once 'connection.php';

$csv_path = __DIR__ . '/data/pollution_export_for_db.csv';
$result = ['success' => false, 'message' => '', 'imported' => 0];

if (!file_exists($csv_path)) {
    $result['message'] = 'ملف data/pollution_export_for_db.csv غير موجود. شغّل خلايا التصدير في Pollution_data.ipynb أولاً.';
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    $conn->beginTransaction();
    // حذف البيانات القديمة (اختياري - أو استخدم INSERT فقط لإضافة سجلات جديدة)
    // $conn->exec("DELETE FROM pollution_data");
    $stmt = $conn->prepare("INSERT INTO pollution_data (location, air_quality_index, water_quality_index, visual_pollution_score, tourist_density, date_recorded, season, notes) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    $handle = fopen($csv_path, 'r');
    $header = fgetcsv($handle);
    $imported = 0;
    while (($row = fgetcsv($handle)) !== false) {
        if (count($row) < 8) continue;
        $stmt->execute([
            $row[0],  // location
            $row[1],  // air_quality_index
            $row[2],  // water_quality_index
            $row[3],  // visual_pollution_score
            $row[4],  // tourist_density
            $row[5],  // date_recorded
            $row[6],  // season
            $row[7] ?? ''  // notes
        ]);
        $imported++;
    }
    fclose($handle);
    $conn->commit();
    $result['success'] = true;
    $result['imported'] = $imported;
    $result['message'] = "تم استيراد $imported سجل بنجاح.";
} catch (Exception $e) {
    $conn->rollBack();
    $result['message'] = 'خطأ: ' . $e->getMessage();
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode($result, JSON_UNESCAPED_UNICODE);
