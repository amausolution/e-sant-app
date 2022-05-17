<?php

namespace Feggu\Core\Admin\Models;

use Feggu\Core\Partner\Models\CategoryAnalysis;
use Feggu\Core\Partner\Models\CategoryAnalysisDetail;

class AdminCategoryAnalysis extends CategoryAnalysis
{

    /**
     * Get category detail in admin
     *
     * @param   [type]  $id  [$id description]
     *
     * @return  [type]       [return description]
     */
    public static function getCategoryAdmin($id)
    {
        return self::where('id', $id)
            ->first();
    }

    public static function getListAllActive()
    {
        return self::where('status',1)->get();
    }
    /**
     * Get tree categories
     *
     * @param   [type]  $parent      [$parent description]
     * @param   [type]  &$tree       [&$tree description]
     * @param   [type]  $categories  [$categories description]
     * @param   [type]  &$st         [&$st description]
     *
     * @return  [type]               [return description]
     */
    public function getTreeCategoriesAdmin($parent = 0, &$tree = [], $categories = null, &$st = '')
    {
        $categories = $categories ?? $this->getListCategoryAnalysisGroupByParentAdmin();
        $categoriesTitle =  $this->getListNameAdmin();
        $tree = $tree ?? [];
        $lisCategory = $categories[$parent] ?? [];
        if ($lisCategory) {
            foreach ($lisCategory as $category) {
                $tree[$category['id']] = $st . ($categoriesTitle[$category['id']]??'');
                if (!empty($categories[$category['id']])) {
                    $st .= '--';
                    $this->getTreeCategoriesAdmin($category['id'], $tree, $categories, $st);
                    $st = '';
                }
            }
        }
        return $tree;
    }


    /**
     * Get array title category
     * user for admin
     *
     * @return  [type]  [return description]
     */
    public static function getListNameAdmin()
    {
        $table = (new CategoryAnalysis)->getTable();

        if (self::$getListTitleAdmin === null) {
            self::$getListTitleAdmin = self::pluck('name', 'id')
                ->toArray();
        }
        return self::$getListTitleAdmin;

    }


    /**
     * Get array title category
     * user for admin
     *
     * @return  [type]  [return description]
     */
    public static function getListCategoryAnalysisGroupByParentAdmin()
    {
        if (self::$getListCategoryGroupByAdmin === null) {
            self::$getListCategoryGroupByAdmin = self::select('id', 'parent')
                ->get()
                ->groupBy('parent')
                ->toArray();
        }
        return self::$getListCategoryGroupByAdmin;
    }


    /**
     * Create a new category
     *
     * @param array $dataInsert [$dataInsert description]
     *
     **/
    public static function createCategoryAdmin(array $dataInsert)
    {
        return self::create($dataInsert);
    }


    /**
     * Insert data description
     *
     * @param   array  $dataInsert  [$dataInsert description]
     *
     * @return  [type]              [return description]
     */
    public static function insertDescriptionAdmin(array $dataInsert)
    {
        return CategoryAnalysisDetail::create($dataInsert);
    }
}
