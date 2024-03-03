<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <title>Online Quiz Select Quiz Type</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/categorystyles.css') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>

<body>

    <div class="logout-container">
        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" style="color: black !important;">Logout</a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>

    <div id="timer-container" class="timer-container" style="display:none;">
        <span id="timer-text">Time Left: <span id="timer">0</span></span>
    </div>

    <div id="question-number-container" class="question-number-container" style="display:none;">
        <span id="question-number">1</span>
    </div>

    <div class="container">
        <header style="margin-left: -29%;">Online Quiz - Select Quiz Type</header>

        <div id="myCarousel" class="carousel slide" data-ride="carousel">

            <div class="carousel-inner">
                @php
                $categoriesChunks = $categories->chunk(6);
                @endphp

                @foreach($categoriesChunks as $index => $subcategoriesJson)
                @php
                $subcategories = json_decode($subcategoriesJson, true);
                @endphp

                <div class="item{{ $index === 0 ? ' active' : '' }}">
                    <div class="oval-grid">
                        @foreach($subcategories as $category => $subcategoriesArray)
                        <div id="start-button" class="oval category" data-category="{{ $category }}" data-subcategories="{{ implode(', ', $subcategoriesArray) }}">
                            {{ $category }}
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
                <br><br><br>

                <div class="row justify-content-center">
                    <div class="col-auto">
                        <ol class="carousel-indicators" id="carousel-indicators">
                            @foreach($categoriesChunks as $index => $_)
                            <li data-target="#myCarousel" data-slide-to="{{ $index }}" class="{{ $index === 0 ? 'active' : '' }}"></li>
                            @endforeach
                        </ol>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="questions-container" style="display:none;">

        <div id="question-container">
            <p class="question-div" id="question-text"></p>

            <div class="answer-div" id="answer-buttons" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px;"></div>

        </div>
    </div>
    </div>

    <div id="all-questions-container">

    </div>
    <br><br>
    <div class="vertical-center resetbtn" id="resetbtn" style="display:none;">
        <button id="reset-button">Reset</button>
    </div>

</body>

</html>

<script>
    $(document).ready(function() {
        $('#carousel-indicators li').on('click', function() {
            console.log('Carousel indicator clicked');
            var slideIndex = $(this).attr('data-slide-to');
            console.log('Slide index:', slideIndex);
            $('#myCarousel').carousel(parseInt(slideIndex));
        });
    });

    $(document).ready(function() {
        $('.category').on('click', function() {
            var subcategories = $(this).data('subcategories');
            fetchQuestions(subcategories);
        });

        function fetchQuestions(subcategories) {
            $.ajax({
                url: 'https://the-trivia-api.com/api/questions',
                method: 'GET',
                data: {
                    limit: 6,
                    categories: subcategories
                },
                success: function(response) {
                    quizQuestions = response.map(question => ({
                        question: question.question,
                        options: question.incorrectAnswers.concat(question.correctAnswer),
                        correctAnswer: question.correctAnswer
                    }));
                    startQuiz();
                },
                error: function(xhr, status, error) {
                    console.error('Failed to fetch questions:', error);
                }
            });
        }

        let quizQuestions = [];
        let currentQuestionIndex = 0;
        let score = 0;
        let timeLeft = 30;
        let timerInterval;

        function startQuiz() {
            $('.container').hide();
            $('.questions-container').show();
            $('.resetbtn').show();
            $('.timer-container').show();
            $('.question-number-container').show();

            displayQuestion();
            startTimer();
        }

        function displayQuestion() {
            const currentQuestion = quizQuestions[currentQuestionIndex];
            const questionNumberContainer = $('#question-number');
            questionNumberContainer.text(currentQuestionIndex + 1);
            $('#question-text').text(currentQuestion.question);
            $('#answer-buttons').empty();
            currentQuestion.options.forEach(option => {
                const button = $('<button>').addClass('answer-button').text(option);
                button.on('click', function() {
                    checkAnswer(option);
                });
                $('#answer-buttons').append(button);
            });
        }

        function startTimer() {
            clearInterval(timerInterval);
            timeLeft = 30;
            $('#timer').text(timeLeft);
            timerInterval = setInterval(function() {
                if (timeLeft <= 0) {
                    clearInterval(timerInterval);
                    checkAnswer(quizQuestions[currentQuestionIndex].options[0]);
                    return;
                }
                $('#timer').text(--timeLeft);
            }, 1000);
        }

        function checkAnswer(selectedOption) {
            const currentQuestion = quizQuestions[currentQuestionIndex];
            if (selectedOption === currentQuestion.correctAnswer) {
                score++;
            }
            currentQuestionIndex++;
            if (currentQuestionIndex < quizQuestions.length) {
                displayQuestion();
                startTimer();
            } else {
                endQuiz();
            }
        }

        function endQuiz() {
            clearInterval(timerInterval);
            $('.questions-container').hide();
            $('#all-questions-container').empty();
            $('.resetbtn').show();
            $('.timer-container').hide();
            $('.question-number-container').hide();

            const resultsHeading = $('<h2>').addClass('result-heading').text('Results');
            $('#all-questions-container').append(resultsHeading);

            quizQuestions.forEach((question, index) => {
                const questionItem = $('<div>').addClass('question-grid-item');
                const questionText = $('<div>').addClass('question-result').text(`${index + 1}: ${question.question}`);
                const answerText = $('<div>').addClass('answer-result').text(`Answer: ${question.correctAnswer}`);
                questionItem.append(questionText, answerText);
                $('#all-questions-container').append(questionItem);
            });

            const totalQuestions = quizQuestions.length;
            const percentage = Math.round((score / totalQuestions) * 100);

            let resultStatus;
            if (percentage >= 60) {
                resultStatus = 'Winner';
            } else if (percentage >= 40) {
                resultStatus = 'Better';
            } else {
                resultStatus = 'Failed';
            }

            const resultStatusText = $('<div>').addClass('result-status').text(` ${resultStatus}`);
            $('#all-questions-container').append(resultStatusText);

            $('#all-questions-container').show();
        }

        $('#reset-button').on('click', function() {
            $('.container').show();
            $('.questions-container').hide();
            $('#all-questions-container').hide();
            $('.timer-container').hide();
            $('.question-number-container').hide();
            $('.resetbtn').hide();
            currentQuestionIndex = 0;
            score = 0;
            clearInterval(timerInterval);
            $('#timer').text('30');
        });

        $('#start-button').on('click', startQuiz);
    });
</script>
