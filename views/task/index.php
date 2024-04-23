<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;

$this->title = 'Add Task';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="task-add">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(['id' => 'task-form']); ?>

    <?= $form->field($task, 'category_id')->dropDownList($categoryList) ?>

    <?= $form->field($task, 'name')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::button('Add', ['class' => 'btn btn-primary', 'id' => 'add-task-btn']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<?= GridView::widget([
    'dataProvider' => new \yii\data\ArrayDataProvider(['allModels' => $tasks]),
    'columns' => [
        'name',
        [
            'label' => 'Category',
            'value' => function ($task) {
                return $task->category ? $task->category->name : 'Uncategorized';
            }
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{update} {delete}',
            'buttons' => [
                'update' => function ($url, $task, $key) {
                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['edit', 'id' => $task->id]);
                },
                'delete' => function ($url, $task, $key) {
                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete', 'id' => $task->id], [
                        'data' => [
                            'confirm' => 'Are you sure you want to delete this task?',
                            'method' => 'post',
                        ],
                    ]);
                },
            ],
        ],
    ],
]); ?>

<script>
    // AJAX request to add a new task
    $('#add-task-btn').on('click', function () {
        var formData = $('#task-form').serialize();
        $.ajax({
            url: '<?= Yii::$app->urlManager->createUrl(['task/add']) ?>',
            type: 'POST',
            data: formData,
            success: function (response) {
                // Handle success response (e.g., show a success message)
                alert('Task added successfully!');
                // Optionally, you can clear the form fields or perform other actions
            },
            error: function () {
                // Handle error response (e.g., show an error message)
                alert('Error occurred while adding task!');
            }
        });
    });
</script>
