
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- Top Bar Navbar -->
    <header class="navbar-section">
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <a class="navbar-brand" href="login.php">
                     Debating Tool
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="#contact">Contact</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="container mt-5">
        <h1>Admin Dashboard</h1>
        <p>Welcome, admin! The competition date is: <?php echo $competition_date; 

ob_start();
session_start();
require_once('connection_pdo.php');

// Ensure only admins can access this page
if (!isset($_SESSION['username_admin'])) {
    header("location:admin_login.php");
    exit();
}

try {
    $stmt = $connection_pdo->query("SELECT competition_date FROM dates WHERE id = 1");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $competition_date = date("d.m.Y", strtotime($row['competition_date']));
    } else {
        echo "Competition date not found.";
    }
} catch (PDOException $e) {
    echo "Error fetching competition date: " . $e->getMessage();
}

// Function to generate the ODT certificate with unique filenames and proper XML handling
function generate_odt_certificate($name, $school, $place, $league, $date) {
global $connection_pdo;  // Ensure the connection is accessible
    $st = $connection_pdo->query("SELECT name FROM host");
    $rw = $st->fetch(PDO::FETCH_ASSOC);
    $hostname = $rw['name']; // This is the school name

    $template = 'vorlage.odt';
    $cleanName = preg_replace('/\s+/', '_', $name); // Ensure no spaces in file names
    $outputFile = 'certificates/urkunde_' . $cleanName . '_' . $place . '.odt'; // Unique file name using place and name

    // Ensure the certificates directory exists
    if (!file_exists('certificates')) {
        mkdir('certificates', 0755, true);
    }

    // Copy template to create a new file for this certificate
    if (!copy($template, $outputFile)) {
        echo 'Failed to copy template.';
        return false;
    }

    // Open the ODT file as a Zip archive
    $zip = new ZipArchive;
    if ($zip->open($outputFile) === true) {
        // Extract content.xml
        $content = $zip->getFromName('content.xml');

        // Encode XML special characters to prevent XML format errors
        $name = htmlspecialchars($name, ENT_XML1, 'UTF-8');
        $school = htmlspecialchars($school, ENT_XML1, 'UTF-8');
        $place = htmlspecialchars($place, ENT_XML1, 'UTF-8');
        $league = htmlspecialchars($league, ENT_XML1, 'UTF-8');
        $date = htmlspecialchars($date, ENT_XML1, 'UTF-8');

        // Add custom styles for Bold and Underline text in the ODT file
        $automaticStyles = '
            <style:style style:name="Bold" style:family="text">
                <style:text-properties fo:font-weight="bold"/>
            </style:style>
            <style:style style:name="Underline" style:family="text">
                <style:text-properties style:text-underline-style="solid" style:text-underline-width="auto" style:text-underline-color="font-color"/>
            </style:style>
        ';

        // Inject the automatic styles into the content.xml
        $content = preg_replace('/(<office:automatic-styles[^>]*>)/', '$1' . $automaticStyles, $content);

        // Replace placeholders with actual values
        $content = str_replace('{{Name}}', '<text:span text:style-name="Underline">' . $name . '</text:span>', $content);
        $content = str_replace('{{School}}', '<text:span text:style-name="Underline">' . $school . '</text:span>', $content);
        $content = str_replace('{{PLACE}}', '<text:span text:style-name="Bold">' . $place . '</text:span>', $content);
        $content = str_replace('{{League}}', $league, $content);
        $content = str_replace('{{Date}}', $date, $content);
        $content = str_replace('{{SCHOOLNAME}}', '<text:span text:style-name="Bold">' . $hostname . '</text:span>', $content);

        // Write modified content.xml back into the ODT file
        $zip->addFromString('content.xml', $content);
        $zip->close();

        return $outputFile; // Return the path to the generated certificate
    } else {
        echo 'Failed to open template file.';
        return false;
    }
}
//generate_odt_certificate("N", "S", "P", "jr", $competition_date);
// Fetch all teams
function fetch_teams($connection_pdo) {
    $teamsStmt = $connection_pdo->query("SELECT * FROM teams ORDER BY team_wins DESC, team_points DESC");
    return $teamsStmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fetch all players and group them by league
function fetch_players_by_league($connection_pdo, $teams) {
    $playersStmt = $connection_pdo->query("SELECT * FROM players");
    $players = $playersStmt->fetchAll(PDO::FETCH_ASSOC);

    $leagues = ['Sr.' => [], 'Jr.' => []];

    foreach ($players as $player) {
        foreach ($teams as $team) {
            if ($player['TeamID'] == $team['Team_ID']) {
                if (strpos($team['team_name'], 'Sr.') !== false) {
                    $leagues['Sr.'][] = ['player' => $player, 'team' => $team];
                } elseif (strpos($team['team_name'], 'Jr.') !== false) {
                    $leagues['Jr.'][] = ['player' => $player, 'team' => $team];
                }
                break;
            }
        }
    }

    return $leagues;
}

// Get top 3 teams from a league
function get_top_teams($teams, $league) {
    $top_teams = [];
    foreach ($teams as $team) {
        if (strpos($team['team_name'], $league) !== false) {
            $top_teams[] = $team;
        }
    }
    usort($top_teams, function($a, $b) {
        return $b['team_wins'] - $a['team_wins'];
    });
    return array_slice($top_teams, 0, 3);
}

function get_best_speakers($players) {
    usort($players, function($a, $b) {
        // Check if player_Max_points exists before using it
        $maxPointsA = isset($a['player_Max_points']) ? $a['player_Max_points'] : 0;
        $maxPointsB = isset($b['player_Max_points']) ? $b['player_Max_points'] : 0;

        return $maxPointsB - $maxPointsA;
    });

    return array_slice($players, 0, 3);
}

// Fetch all teams
$teams = fetch_teams($connection_pdo);

// Fetch all players and divide them into Sr. and Jr. leagues
$leagues = fetch_players_by_league($connection_pdo, $teams);



// Get Top 3 teams for Senior League
$top_teams_sr = get_top_teams($teams, 'Sr.');
$top_teams_jr = get_top_teams($teams, 'Jr.');




// Fetch all teams and players
$teams = fetch_teams($connection_pdo);
$leagues = fetch_players_by_league($connection_pdo, $teams);

// Fetch top best speakers (1st, 2nd, 3rd)
$best_speakers_sr = get_best_speakers($leagues['Sr.']);
$best_speakers_jr = get_best_speakers($leagues['Jr.']);

// Function to display teams, players, and generate certificates
function display_top_teams($leagues, $top_teams, $league_name, $competition_date) {
    $places = ['FIRST PLACE', 'SECOND PLACE', 'THIRD PLACE'];

    for ($i = 0; $i < count($top_teams); $i++) {
        echo "<h3>{$places[$i]} Place Team ({$league_name})</h3>";
        echo "<table border='1'>";
        echo "<tr><th>Player Name</th><th>Team School</th><th>Points</th><th>Max Points</th><th>Wins</th><th>Top 3 Team</th><th>Download Certificate</th></tr>";

        foreach ($leagues as $entry) {
            $player = $entry['player'];
            $team = $entry['team'];
            if ($team == $top_teams[$i]) {
                // Generate ODT certificate and get the file path
                $place = strtoupper($places[$i]);
                $certificate_file = generate_odt_certificate($player['player_name'], $team['team_school'], $place, strtolower($league_name), $competition_date);

                echo "<tr>";
                echo "<td>{$player['player_name']}</td>";
                echo "<td>{$team['team_school']}</td>";
                echo "<td>{$player['player_points']}</td>";
                echo "<td>" . (isset($player['player_Max_points']) ? $player['player_Max_points'] : 'N/A') . "</td>";
                echo "<td>{$player['player_wins']}</td>";
                echo "<td>Yes</td>";
                if ($certificate_file) {
                    echo "<td><a href='$certificate_file' download>Download Certificate</a></td>";
                } else {
                    echo "<td>Failed to generate certificate</td>";
                }
                echo "</tr>";
            }
        }

        echo "</table>";
    }
}

// Display Senior League top teams
display_top_teams($leagues['Sr.'], $top_teams_sr, 'senior', $competition_date);

// Display Junior League top teams
display_top_teams($leagues['Jr.'], $top_teams_jr, 'junior', $competition_date);

function display_best_speaker_table($best_speakers, $league_name, $competition_date) {
    // Sort the speakers by player_Max_points in descending order
    usort($best_speakers, function($a, $b) {
        return $b['player']['player_Max_points'] - $a['player']['player_Max_points'];
    });

    echo "<h3>Top 3 Best Speakers ($league_name)</h3>";
    echo "<table border='1'>";
    echo "<tr><th>Rank</th><th>Player Name</th><th>Team School</th><th>Points</th><th>Download Best Speaker Certificate</th></tr>";

    $rank_names = ['1st Best Speaker', '2nd Best Speaker', '3rd Best Speaker'];

    // Loop through the sorted list, but only display top 3
    for ($i = 0; $i < 3 && $i < count($best_speakers); $i++) {
        $entry = $best_speakers[$i];
        $player = $entry['player'];
        $team = $entry['team'];

        // Generate best speaker certificate with rank (1st, 2nd, 3rd)
        $certificate_file = generate_odt_certificate($player['player_name'], $team['team_school'], $rank_names[$i], $league_name, $competition_date);

        echo "<tr>";
        echo "<td>{$rank_names[$i]}</td>";
        echo "<td>{$player['player_name']}</td>";
        echo "<td>{$team['team_school']}</td>";
        echo "<td>{$player['player_Max_points']}</td>";

        if ($certificate_file) {
            echo "<td><a href='$certificate_file' download>Download Certificate</a></td>";
        } else {
            echo "<td>Failed to generate certificate</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
}

// Display Best Speakers for both Senior and Junior leagues
display_best_speaker_table($best_speakers_sr, "senior", $competition_date);
display_best_speaker_table($best_speakers_jr, "junior", $competition_date);

ob_end_flush();
?></p>
        <!-- Additional admin content here -->
    </main>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-12 col-sm-12">
                    <p class="logo">
                         Debating Tool
                    </p>
                </div>
                <div class="col-lg-5 col-md-12 col-sm-12">
                    <ul class="d-flex">
                        <li><a href="admin_login.php">Home</a></li>
                        <li><a href="#contact">Contact</a></li>
                        <li><a href="admin_login.php">Admin</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-12 col-sm-12">
                    <p>&copy;
                        <a href="https://github.com/Cybersky4" target="_blank" style="color: inherit; text-decoration: underline;">2024_Cyber</a>,
                        <a href="https://github.com/silverhadch" target="_blank" style="color: inherit; text-decoration: underline;">Hadi</a>
                    </p>
                </div>
                <div class="col-lg-1 col-md-12 col-sm-12">
                    <!-- Back to top -->
                    <a href="#" class="back-to-top d-flex align-items-center justify-content-center">
                        <i class="bi bi-arrow-up-short"></i>
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
            crossorigin="anonymous"></script>
</body>
</html>