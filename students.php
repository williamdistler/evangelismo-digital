<?php 
  $instructorId = $_GET['instructor_id'];
  
  // Get Instructor
  $instructorsFiles = glob('./json/instructors/*.json');
  $studentsFiles = glob('./json/students/*.json');
  $i = 1;
  global $totalStudents;
  global $instructorActiveStudentsNumber;
  global $instructorInactiveStudentsNumber;
  foreach ($instructorsFiles as $instructorFile) {
    $instructorFileContent = file_get_contents($instructorFile);
    $jsonInstructorData = json_decode($instructorFileContent, true);
    if ($instructorId === $jsonInstructorData['_id']) {
      $instructorName = $jsonInstructorData['nmCompleto'];
      $totalStudents = count($jsonInstructorData['lista_estudantes']);
      $instructorStudents = $jsonInstructorData['lista_estudantes'];
    }
  }

  // Count Active Students
  foreach ($studentsFiles as $studentFile) {
    $studentFileContent = file_get_contents($studentFile);
    $jsonStudentData = json_decode($studentFileContent, true);
    $activeStudent = $jsonStudentData['flIsEstudanteAtivo'];
    if (in_array($jsonStudentData['_id'], $instructorStudents)) {
      if ($jsonStudentData['flIsEstudanteAtivo'] == true) {
        $instructorActiveStudentsNumber++;
      } else {
        $instructorInactiveStudentsNumber++;
      }
    }
  }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Evangelismo Digital</title>
    <link rel="stylesheet" href="students.css">
</head>
<body>
    <div id="app">
        <header>
            <h1>Esperança - Página de Acompanhamento de Alunos</h1>
        </header>
        <main>
          <h3>Ordenar por:</h3>
          <select name="orderBy" id="orderBy">
            <option value="">Selecione uma opção</option>
            <option value="active">Ativos</option>
            <option value="inactive">Inativos</option>
            <option value="alphabetical">Ordem Alfabética</option>
          </select>
          <div class="table">
            <div class="head-section">
              <h2>
                Alunos acompanhados por:                 
                <?php
                  echo $instructorName;
                ?>
              </h2>
              <div>
                Total de estudantes: <?php echo $totalStudents ?> 
                | 
                Total de estudantes ativos: <?php echo $instructorActiveStudentsNumber ?>
                |
                Total de estudantes inativos: <?php echo $instructorInactiveStudentsNumber ?>
                </div>
            </div>
            <div class="table-section">
              <div>#</div>
              <div>Nome</div>
              <div>Ativo</div>
            </div>
            <?php
                foreach ($studentsFiles as $studentFile) {
                  $studentFileContent = file_get_contents($studentFile);
                  $jsonStudentData = json_decode($studentFileContent, true);
                  if (in_array($jsonStudentData['_id'], $instructorStudents)) {
                    $studantName = $jsonStudentData['nmCompleto'];
                    $isActive = $jsonStudentData['flIsEstudanteAtivo'] ? 'Sim' : 'Não';
                    echo "
                      <div class='table-section'>
                        <div>{$i}</div>
                        <div>{$studantName}</div>
                        <div>{$isActive}</div>
                      </div>
                    ";
                    $i++;                    
                  }
                }
            ?>
          </div>
        </main>
        <footer>
            <div id="cr">Copyright &copy; 2024 Desenvolvido por William Distler Neves</div>
            <div>Version 1.0</div>
        </footer>
    </div>
</body>
</html>