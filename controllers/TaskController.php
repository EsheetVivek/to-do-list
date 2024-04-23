<?php

namespace app\controllers;

use Yii;
use app\models\Task;
use app\models\Category;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class TaskController extends Controller
{
    public function actionIndex()
    {
        $tasks = Task::find()->with('category')->all();
        $categories = Category::find()->all();
        $categoryList = \yii\helpers\ArrayHelper::map($categories, 'id', 'name');

        return $this->render('index', [
            'task' => $tasks,
            'categoryList' => $categoryList, 
        ]);
    }

    public function actionAdd()
    {
        $task = new Task();
        $categories = Category::find()->all();
        
        if ($task->load(Yii::$app->request->post()) && $task->save()) {
            Yii::$app->session->setFlash('success', 'Task added successfully.');
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['success' => true];
        } else {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['success' => false, 'errors' => $task->errors, 'categories' => $categories];
        }
    }

    public function actionEdit($id)
    {
        $task = $this->findTask($id);
        $categories = Category::find()->all(); 
        
        if ($task->load(Yii::$app->request->post()) && $task->save()) {
            Yii::$app->session->setFlash('success', 'Task updated successfully.');
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['success' => true];
        } else {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['success' => false, 'errors' => $task->errors, 'categories' => $categories];
        }
    }

    public function actionDelete($id)
    {
        $task = $this->findTask($id);
        $task->delete();

        Yii::$app->session->setFlash('success', 'Task deleted successfully.');
        return $this->redirect(['index']);
    }

    protected function findTask($id)
    {
        $task = Task::findOne($id);
        
        if ($task !== null) {
            return $task;
        }

        throw new NotFoundHttpException('The requested task does not exist.');
    }
}