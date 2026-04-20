<?php
require_once __DIR__ . '/bootstrap.php';
?>
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beheer Jouw Gids</title>
    <link href="/src/output.css" rel="stylesheet">
</head>

<body class="bg-background min-h-screen flex flex-col">
    <main class="flex-grow">
        <?php
        try {
            loadPage();
        } catch (HttpException $e) {
            http_response_code($e->getStatusCode());
            Logger::getInstance()->warning('HTTP Exception: ' . $e->getMessage(), ['status' => $e->getStatusCode()]);

            $statusCode = $e->getStatusCode();
            $errorView = __DIR__ . "/views/errors/{$statusCode}.php";
            $errorViewWithSuffix = __DIR__ . "/views/errors/{$statusCode}.view.php";

            if (file_exists($errorView)) {
                require $errorView;
            } elseif (file_exists($errorViewWithSuffix)) {
                require $errorViewWithSuffix;
            } else {
                echo $e->getMessage();
            }
        } catch (Throwable $e) {
            http_response_code(500);
            Logger::getInstance()->error('Uncaught exception: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            require __DIR__ . "/views/errors/500.php";
        }
        ?>
    </main>
</body>

</html>