<?php

declare(strict_types=1);

namespace app\models\search;

use app\models\Book;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class BookSearch extends Model
{
    public ?string $title = null;
    public ?int $year = null;
    public ?string $isbn = null;

    public function rules(): array
    {
        return [
            [['title', 'isbn'], 'safe'],
            [['year'], 'integer'],
        ];
    }

    public function search(array $params): ActiveDataProvider
    {
        $query = Book::find()->with('authors');

        $provider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['year' => SORT_DESC, 'title' => SORT_ASC]],
            'pagination' => ['pageSize' => 20],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $provider;
        }

        $query->andFilterWhere(['year' => $this->year]);
        $query->andFilterWhere(['like', 'title', $this->title]);
        $query->andFilterWhere(['like', 'isbn', $this->isbn]);

        return $provider;
    }
}
