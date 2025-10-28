<?php<?php



/**/**

 * Script to update all ApiResources models with proper #[ApiResource] attributes * Script to add routePrefix: '/api/v1/admin' to all #[ApiResource] attributes in Models

 */ * Run: php update_models.php

 */

$modelsPath = '/home/users/navneet.kumar/Bagisto/workspaces/bagisto-api-platform/packages/Webkul/ApiResources/src/Models';

$modelsDir = __DIR__ . '/packages/Webkul/ApiResources/src/Models';

// Get all PHP files recursively

$files = new RecursiveIteratorIterator(function updateFile($filePath, $modelsDir) {

    new RecursiveDirectoryIterator($modelsPath),    $content = file_get_contents($filePath);

    RecursiveIteratorIterator::LEAVES_ONLY    

);    // Pattern to find #[ApiResource(...)]

    $pattern = '/#\[ApiResource\s*\(/';

$phpFiles = [];    

foreach ($files as $file) {    if (!preg_match($pattern, $content)) {

    if ($file->getExtension() === 'php') {        return false;

        $phpFiles[] = $file->getRealPath();    }

    }    

}    // Check if routePrefix already exists

    if (strpos($content, "routePrefix:") !== false) {

echo "Found " . count($phpFiles) . " PHP files\n";        // Already has it, skip

        return false;

$updated = 0;    }

$skipped = 0;    

    // Find the position of #[ApiResource(

foreach ($phpFiles as $filePath) {    $pos = strpos($content, '#[ApiResource(');

    $content = file_get_contents($filePath);    if ($pos === false) {

    $fileName = basename($filePath, '.php');        return false;

        }

    // Generate description from file name (convert CamelCase to spaces)    

    $description = preg_replace('/([A-Z])/', ' $1', $fileName);    // Find the opening ( and what comes after

    $description = trim($description) . ' resource';    $openParen = strpos($content, '(', $pos);

        if ($openParen === false) {

    // Check if file has #[ApiResource        return false;

    if (strpos($content, '#[ApiResource') === false) {    }

        echo "⊘ SKIP: $fileName - No ApiResource found\n";    

        $skipped++;    // Check if there's a newline after (

        continue;    $afterParen = $openParen + 1;

    }    $nextChar = $content[$afterParen];

        

    // Pattern 1: #[ApiResource()] - empty parentheses    if ($nextChar === ')') {

    if (preg_match('/#\[ApiResource\(\)\]/', $content)) {        // Empty parentheses: #[ApiResource()]

        $old = '#[ApiResource()]';        $newContent = substr_replace($content, "(\n    routePrefix: '/api/v1/admin'", $openParen, 1);

        $new = "#[ApiResource(\n    description: '$description',\n    routePrefix: '/api/v1/admin',\n    security: \"is_granted('ROLE_ADMIN')\"\n)]";    } elseif ($nextChar === '\n' || $nextChar === ' ') {

        $content = str_replace($old, $new, $content);        // Has attributes, insert before first attribute

        file_put_contents($filePath, $content);        $newContent = substr_replace($content, "(\n    routePrefix: '/api/v1/admin',\n    ", $openParen, 1);

        echo "✓ UPDATE: $fileName - Added complete ApiResource\n";    } else {

        $updated++;        // Single line format: #[ApiResource(shortName: ...

        continue;        $newContent = substr_replace($content, "(\n    routePrefix: '/api/v1/admin',\n    ", $openParen, 1);

    }    }

        

    // Pattern 2: #[ApiResource] - no parentheses    if ($newContent !== $content) {

    if (preg_match('/#\[ApiResource\]/', $content)) {        file_put_contents($filePath, $newContent);

        $old = '#[ApiResource]';        echo "✓ UPDATED: " . str_replace($modelsDir . '/', '', $filePath) . "\n";

        $new = "#[ApiResource(\n    description: '$description',\n    routePrefix: '/api/v1/admin',\n    security: \"is_granted('ROLE_ADMIN')\"\n)]";        return true;

        $content = str_replace($old, $new, $content);    }

        file_put_contents($filePath, $content);    

        echo "✓ UPDATE: $fileName - Added complete ApiResource\n";    return false;

        $updated++;}

        continue;

    }function scanDirectory($dir, $modelsDir) {

        $files = scandir($dir);

    // Pattern 3: Already has some properties - check if needs update    $count = 0;

    if (preg_match('/#\[ApiResource\([^)]*\)\]/s', $content)) {    

        // Check if it already has routePrefix    foreach ($files as $file) {

        if (strpos($content, "routePrefix: '/api/v1/admin'") !== false) {        if ($file === '.' || $file === '..') {

            echo "✓ DONE: $fileName - Already has correct routePrefix\n";            continue;

            $updated++;        }

            continue;        

        }        $path = $dir . '/' . $file;

                

        // Check if it has description        if (is_dir($path)) {

        if (strpos($content, 'description:') === false) {            $count += scanDirectory($path, $modelsDir);

            // Add description before routePrefix        } elseif (is_file($path) && substr($file, -4) === '.php') {

            $pattern = '/#\[ApiResource\(\n/';            if (updateFile($path, $modelsDir)) {

            if (preg_match($pattern, $content)) {                $count++;

                $replacement = "#[ApiResource(\n    description: '$description',\n";            }

                $content = preg_replace($pattern, $replacement, $content, 1);        }

            }    }

        }    

            return $count;

        // Add or update routePrefix}

        if (strpos($content, "routePrefix:") === false) {

            $pattern = '/(\s+)(security:|rules:|)\n(?=\s*\)]/';echo "Starting update of ApiResource models...\n";

            if (preg_match($pattern, $content)) {echo "========================================\n\n";

                $replacement = "\n    routePrefix: '/api/v1/admin',\n$1$2\n";

                $content = preg_replace($pattern, $replacement, $content, 1);$updated = scanDirectory($modelsDir, $modelsDir);

            }

        }echo "\n========================================\n";

        echo "Total files updated: $updated\n";

        file_put_contents($filePath, $content);echo "Done!\n";

        echo "✓ UPDATE: $fileName - Updated ApiResource attributes\n";?>

        $updated++;
        continue;
    }
    
    $skipped++;
}

echo "\n====================\n";
echo "Updated: $updated\n";
echo "Skipped: $skipped\n";
echo "Total: " . count($phpFiles) . "\n";
?>
