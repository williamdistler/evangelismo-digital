<?php
  // Get coordenador
  $jsonCoordinatorString = file_get_contents('./json/coordinator/coordenador.json');
  $jsonCoordinatorData = json_decode($jsonCoordinatorString, true);

  // Get Instructors
  $instructorsFiles = glob('./json/instructors/*.json');
  $studentsFiles = glob('./json/students/*.json');
  $i = 1;
  global $totalStudents;
  global $totalActiveStudents;
  global $totalInactiveStudents;
  global $activeStudent;
  global $instructorActiveStudentsNumber;
  global $instructorInactiveStudentsNumber;
  foreach ($instructorsFiles as $instructorFile) {
    $instructorFileContent = file_get_contents($instructorFile);
    $jsonInstructorData = json_decode($instructorFileContent, true);
    $instructorStudentsNumber = count($jsonInstructorData['lista_estudantes']);
    $totalStudents += $instructorStudentsNumber;
  }

  // Count Active Students
  foreach ($studentsFiles as $studentFile) {
    $studentFileContent = file_get_contents($studentFile);
    $jsonStudentData = json_decode($studentFileContent, true);
    $activeStudent = $jsonStudentData['flIsEstudanteAtivo'];
    if ($activeStudent == true) {
      $totalActiveStudents++;
    } else {
      $totalInactiveStudents++;
    }
  }

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Evangelismo Digital</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div id="app">
        <header>
            <h1>Esperança - Página de Acompanhamento de Instrutor</h1>
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
              <h2>Instrutores acompanhados por: 
                <?php
                  echo $jsonCoordinatorData['nmCompleto'];
                ?>
              </h2>
              <div>
                Total de estudantes: <?php echo $totalStudents ?> 
                | 
                Total de estudantes ativos: <?php echo $totalActiveStudents ?>
                |
                Total de estudantes inativos: <?php echo $totalInactiveStudents ?>
              </div>
            </div>
            <div class="table-section">
              <div>#</div>
              <div>Nome</div>
              <div>Total de Estudantes</div>
              <div>Total de Ativos</div>
              <div>Total de Inativos</div>
              <div></div>
            </div>
            <?php
              foreach ($instructorsFiles as $file) {
                $fileContent = file_get_contents($file);
                $jsonInstructorData = json_decode($fileContent, true);
                $instructorId = $jsonInstructorData['_id'];
                $instructorName = $jsonInstructorData['nmCompleto'];
                $instructorStudents = $jsonInstructorData['lista_estudantes'];
                $instructorStudentsNumber = count($jsonInstructorData['lista_estudantes']);
                $instructorActiveStudentsNumber = 0;
                $instructorInactiveStudentsNumber = 0;    
                foreach ($studentsFiles as $studentFile) {
                  $studentFileContent = file_get_contents($studentFile);
                  $jsonStudentData = json_decode($studentFileContent, true);
                  if (in_array($jsonStudentData['_id'], $instructorStudents)) {
                    $jsonStudentData['flIsEstudanteAtivo'] == true 
                    ? 
                    $instructorActiveStudentsNumber++ 
                    : 
                    $instructorInactiveStudentsNumber++;
                  }
                }
                echo "
                  <div class='table-section'>
                    <div>{$i}</div>
                    <div>{$instructorName}</div>
                    <div>{$instructorStudentsNumber}</div>
                    <div>{$instructorActiveStudentsNumber}</div>
                    <div>{$instructorInactiveStudentsNumber}</div>
                    <div class='instructor-functions'>
                      <button class='list-button' data-instructor-id='{$instructorId}' href='./students.php'>
                        <img src='./assets/list-icon.png' alt=''>
                      </button>
                    </div>
                  </div>
                ";
                $i++;
              }
            ?>
            <div class="table-section">
              <div></div>
              <div>Total:</div>
              <div><?php echo $totalStudents ?></div>
              <div><?php echo $totalActiveStudents ?></div>
              <div><?php echo $totalInactiveStudents ?></div>
              <div></div>
            </div>
          </div>
        </main>
        <footer>
            <div id="cr">Copyright &copy; 2024 Desenvolvido por William Distler Neves</div>
            <div>Version 1.0</div>
        </footer>
    </div>
</body>
</html>

<script>
  const botoes = document.querySelectorAll('.list-button');

  botoes.forEach(botao => {
    botao.addEventListener('click', function() {
      const idInstrutor = this.dataset.instructorId; // Obter ID do instrutor do atributo de dados
      window.location.href = `students.php?instructor_id=${idInstrutor}`; // Redirecionar com ID do instrutor na string de consulta
    });
  });
</script>
