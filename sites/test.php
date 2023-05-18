<!DOCTYPE html>
<html>

<head>
    <title>Статьи</title>
</head>

<body>
    <?php
    // Функция для выполнения запросов с Basic Authentication
    function curlRequest($url, $username, $password)
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, "$username:$password");
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    // Запрос списка категорий статей
    $categoriesUrl = 'https://test.labsales.ru/tasks/articles/rest/categories';
    $response = curlRequest($categoriesUrl, 'labsales_test', '18765gR5');
    $data = json_decode($response, true);

    if (!empty($data['error'])) {
        echo 'Ошибка при запросе категорий: ' . $data['error'];
        exit;
    }

    $categories = $data['data'];

    // Обработка клика по категории
    if (isset($_GET['category_id'])) {
        $selectedCategoryId = $_GET['category_id'];

        // Запрос статей для выбранной категории
        $articlesUrl = "https://test.labsales.ru/tasks/articles/rest/category/$selectedCategoryId";
        $response = curlRequest($articlesUrl, 'labsales_test', '18765gR5');
        $data = json_decode($response, true);

        if (!empty($data['error'])) {
            echo 'Ошибка при запросе статей: ' . $data['error'];
            exit;
        }

        $articles = $data['data'];

        // Вывод статей
        echo '<h2>Выбранная категория</h2>';
        echo '<ul>';
        foreach ($articles as $article) {
            $articleId = $article['article_id'];
            $articleName = $article['name'];

            echo '<li>';
            echo "<a href=\"?category_id=$selectedCategoryId&article_id=$articleId\">$articleName</a>";
            echo '</li>';
        }
        echo '</ul>';
    }

    // Обработка клика по статье
    if (isset($_GET['article_id'])) {
        $selectedArticleId = $_GET['article_id'];

        // Запрос данных конкретной статьи
        $articleUrl = "https://test.labsales.ru/tasks/articles/rest/article/$selectedArticleId";
        $response = curlRequest($articleUrl, 'labsales_test', '18765gR5');
        $data = json_decode($response, true);

        if (!empty($data['error'])) {
            echo 'Ошибка при запросе статьи: ' . $data['error'];
            exit;
        }

        $article = $data['data'];

        // Вывод данных статьи
        echo '<h2>Выбранная статья</h2>';
        echo '<h3>' . $article['name'] . '</h3>';
        echo '<p>' . $article['text'] . '</p>';
    }
    // Вывод списка категорий
    echo '<ul>';
    foreach ($categories as $category) {
        $categoryId = $category['category_id'];
        $categoryName = $category['name'];

        echo '<li>';
        echo "<a href=\"?category_id=$categoryId\">$categoryName</a>";
        echo '</li>';
    }
    echo '</ul>';
    ?>
</body>

</html>