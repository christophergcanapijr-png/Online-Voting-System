<?php
include('session.php');
include('dbcon.php');

header('Content-Type: application/json');

if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

try {
    $candidates = isset($_POST['candidates']) ? $_POST['candidates'] : [];
    $academic_year = $_POST['academic_year'] ?? '';

    if (empty($candidates) || !$academic_year) {
        throw new Exception('Missing required parameters');
    }

    $data = [
        'labels' => ['1st Year', '2nd Year', '3rd Year', '4th Year'],
        'datasets' => []
    ];

    $colors = [
        'rgba(128, 0, 0, 0.7)',
        'rgba(0, 128, 0, 0.7)',
        'rgba(0, 0, 128, 0.7)',
        'rgba(128, 128, 0, 0.7)',
        'rgba(128, 0, 128, 0.7)'
    ];

    foreach ($candidates as $i => $candidateId) {
        $stmt = mysqli_prepare($conn, 
            "SELECT 
                CONCAT(c.FirstName, ' ', c.LastName) AS name,
                c.Position,
                (SELECT COUNT(*) FROM votes v 
                 JOIN voters vt ON v.voter_id = vt.StudentID 
                 WHERE v.CandidateID = c.CandidateID 
                 AND vt.Year = '1st Year' AND v.academic_year = ? AND vt.academic_year = ?) / 
                NULLIF((SELECT COUNT(*) FROM voters WHERE Year = '1st Year' AND academic_year = ?), 0) * 100 AS y1,

                (SELECT COUNT(*) FROM votes v 
                 JOIN voters vt ON v.voter_id = vt.StudentID 
                 WHERE v.CandidateID = c.CandidateID 
                 AND vt.Year = '2nd Year' AND v.academic_year = ? AND vt.academic_year = ?) / 
                NULLIF((SELECT COUNT(*) FROM voters WHERE Year = '2nd Year' AND academic_year = ?), 0) * 100 AS y2,

                (SELECT COUNT(*) FROM votes v 
                 JOIN voters vt ON v.voter_id = vt.StudentID 
                 WHERE v.CandidateID = c.CandidateID 
                 AND vt.Year = '3rd Year' AND v.academic_year = ? AND vt.academic_year = ?) / 
                NULLIF((SELECT COUNT(*) FROM voters WHERE Year = '3rd Year' AND academic_year = ?), 0) * 100 AS y3,

                (SELECT COUNT(*) FROM votes v 
                 JOIN voters vt ON v.voter_id = vt.StudentID 
                 WHERE v.CandidateID = c.CandidateID 
                 AND vt.Year = '4th Year' AND v.academic_year = ? AND vt.academic_year = ?) / 
                NULLIF((SELECT COUNT(*) FROM voters WHERE Year = '4th Year' AND academic_year = ?), 0) * 100 AS y4

            FROM candidate c
            WHERE c.CandidateID = ? AND c.academic_year = ?"
        );

        mysqli_stmt_bind_param($stmt, "ssssssssssssii", 
            $academic_year, $academic_year, $academic_year, 
            $academic_year, $academic_year, $academic_year, 
            $academic_year, $academic_year, $academic_year, 
            $academic_year, $academic_year, $academic_year,
            $candidateId, $academic_year
        );

        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);

        if (!$row) continue;

        $data['datasets'][] = [
            'label' => $row['name'] . ' (' . $row['Position'] . ')',
            'data' => [
                round($row['y1'] ?? 0, 1),
                round($row['y2'] ?? 0, 1),
                round($row['y3'] ?? 0, 1),
                round($row['y4'] ?? 0, 1)
            ],
            'backgroundColor' => $colors[$i % count($colors)],
            'borderColor' => str_replace('0.7', '1', $colors[$i % count($colors)]),
            'borderWidth' => 1
        ];
    }

    echo json_encode([
        'success' => true,
        'data' => $data
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
