<?php
use Illuminate\Database\Eloquent\Builder;

/**
 * Paginator.php
 *
 * 分页
 * 作者: zhengzean (andyzheng1024@gmail.com)
 * 创建日期: 16/6/1 上午10:32
 */
class Paginator
{

    /**
     * 分页
     *
     * @param Builder $builder eloquent builder对象
     * @param bool    $isAjax  是否是ajax分页,如果是ajax分页,将不返回分页连接
     * @param Page    $page    页面信息
     * @param string  $style   分页样式
     *
     * @return array
     */
    public static function paginate(Builder $builder, $isAjax = false, Page $page = null, $style = null)
    {
        $page = $page ?: new Page(1);
        $page->totalCount = $builder->toBase()->getCountForPagination();
        $page->per = $page->per ?: $builder->getModel()->getPerPage();
        $items = $builder->skip($page->per * ($page->current - 1))->take($page->per)->get();
        $page->total = $page->totalCount % $page->per == 0 ? $page->totalCount / $page->per : intval($page->totalCount / $page->per) + 1;

        $pagenator = [
            'items' => $items,
            'page' => $page,
        ];

        if ($isAjax) {
            $links = self::generateAjaxLinks($page->current, $page->total, $style);
            $pagenator['links'] = $links;
        } else {
            $links = self::generateLinks($page->current, $page->total, $style);
            $pagenator['links'] = $links;
        }

        return $pagenator;
    }

    /**
     * 生成分页链接
     *
     * @param integer $currentPage 当前页
     * @param integer $totalPage   总页数
     *
     * @param null    $style
     *
     * @return string
     */
    public static function generateLinks($currentPage, $totalPage, $style = null)
    {
        $html = '<ul class="pagination">';
        if ($currentPage == 1) {
            $html .= "<li class=\"disabled\"><a href=\"javascript:void(0)\">&laquo;</a></li>";
        } else {
            $html .= "<li><a href=\"?page=" . ($currentPage - 1) . "\">&laquo;</a></li>";
        }
        if ($totalPage < 10) {
            for ($i = 1; $i <= $totalPage; $i++) {
                $active = $i == $currentPage ? 'class="active"' : '';
                $html .= "<li $active><a href=\"?page=" . $i . "\">$i</a></li>";
            }
        } else {
            if ($currentPage > 3) {
                $start = $currentPage - 2;
            } else {
                $start = 1;
            }

            for ($i = $start; $i <= $currentPage; $i++) {
                $active = $i == $currentPage ? 'class="active"' : '';
                $html .= "<li $active><a href=\"?page=" . $i . "\">$i</a></li>";
            }

            for ($i = $currentPage + 1; $i <= $currentPage + 3 && $i <= $totalPage; $i++) {
                $active = $i == $currentPage ? 'class="active"' : '';
                $html .= "<li $active><a href=\"?page=" . $i . "\">$i</a></li>";
            }

            if ($totalPage - $currentPage >= 5) {
                $html .= "<li><a href='javascript:void(0)'>...</a></li>";
                $html .= "<li><a href=\"?page=" . $totalPage . "\">$totalPage</a></li>";
            }
        }
        if ($currentPage == $totalPage) {
            $html .= "<li class=\"disabled\"><a href=\"javascript:void(0)\">&raquo;</a></li>";
        } else {
            $html .= "<li><a href=\"?page=" . ($currentPage + 1) . "\">&raquo;</a></li>";
        }

        if ($style) {
            $html = '<div ' . $style . '>' . $html . '</div>';
        }
        $html .= '</ul>';
        return $html;
    }

    /**
     * 生成ajax分页链接
     *
     * @param integer $currentPage 当前页
     * @param integer $totalPage   总页数
     *
     * @param null    $style
     *
     * @return string
     */
    public static function generateAjaxLinks($currentPage, $totalPage, $style = null)
    {
        $html = '<ul class="pagination">';
        if ($currentPage == 1) {
            $html .= "<li class=\"disabled\"><a href=\"javascript:void(0)\">&laquo;</a></li>";
        } else {
            $html .= "<li><a href=\"javascript:void(0)\" data-page=" . ($currentPage - 1) . ">&laquo;</a></li>";
        }
        if ($totalPage < 10) {
            for ($i = 1; $i <= $totalPage; $i++) {
                $active = $i == $currentPage ? 'class="active"' : '';
                $html .= "<li $active><a href=\"javascript:void(0)\" data-page=\"$i\">$i</a></li>";
            }
        } else {
            if ($currentPage > 3) {
                $start = $currentPage - 2;
            } else {
                $start = 1;
            }

            for ($i = $start; $i <= $currentPage; $i++) {
                $active = $i == $currentPage ? 'class="active"' : '';
                $html .= "<li $active><a href=\"javascript:void(0)\" data-page=\"$i\">$i</a></li>";
            }

            for ($i = $currentPage + 1; $i <= $currentPage + 3 && $i <= $totalPage; $i++) {
                $active = $i == $currentPage ? 'class="active"' : '';
                $html .= "<li $active><a href=\"javascript:void(0)\" data-page=\"$i\">$i</a></li>";
            }

            if ($totalPage - $currentPage >= 5) {
                $html .= "<li><a href='javascript:void(0)'>...</a></li>";
                $html .= "<li><a href=\"javascript:void(0)\" data-page=\"$totalPage\">$totalPage</a></li>";
            }
        }
        if ($currentPage == $totalPage) {
            $html .= "<li class=\"disabled\"><a href=\"javascript:void(0)\">&raquo;</a></li>";
        } else {
            $html .= "<li><a href=\"javascript:void(0)\" data-page=" . ($currentPage - 1) . ">&raquo;</a></li>";
        }

        if ($style) {
            $html = '<div ' . $style . '>' . $html . '</div>';
        }
        $html .= '</ul>';
        return $html;
    }
}
