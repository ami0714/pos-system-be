<?php

namespace App\Services;
use Illuminate\Support\Facades\DB;
class DashboardService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }


    public function getKpi($filter, $startDate, $endDate){
          if(empty($filter) ){
            return [
                'status' => false,
                'message' => 'data kosong',
            ];
          }

          if(empty($startDate && $endDate) && $filter != 'custom date'){
            switch ($filter) {
                case 'ALL':
                    $query = 'SELECT (SELECT SUM(grand_total) 
                            FROM sales)AS grandTotal, 
                            COALESCE(SUM((si.sell_price - si.buy_price)*si.quantity),0) AS netProfit,
                             (SELECT COUNT(*) FROM sales) AS transactions, (SELECT COALESCE(AVG(grand_total),0) FROM sales) 
                             AS avg_receipt 
                             FROM sales s 
                             LEFT JOIN sale_items si ON s.id = si.sale_id';
                    $dashboard = DB::select($query);

                    return $dashboard;
                    

                case 'weekly':
                   $query ='SELECT (SELECT SUM(grand_total) 
                            FROM sales WHERE status = ? AND created_at BETWEEN CURDATE()-INTERVAL 6 DAY AND  CURDATE() + INTERVAL 1 DAY )AS grandTotal, 
                            COALESCE(SUM((si.sell_price - si.buy_price)*si.quantity),0) AS netProfit,
                             (SELECT COUNT(*) FROM sales WHERE status = ? AND created_at BETWEEN CURDATE()-INTERVAL 6 DAY AND  CURDATE() + INTERVAL 1 DAY ) AS transactions, 
                             (SELECT COALESCE(AVG(grand_total),0) FROM sales WHERE created_at BETWEEN CURDATE()-INTERVAL 6 DAY AND  CURDATE() + INTERVAL 1 DAY ) 
                             AS avg_receipt 
                             FROM sales s 
                             LEFT JOIN sale_items si ON s.id = si.sale_id
                             WHERE s.status = ? AND s.created_at BETWEEN CURDATE()-INTERVAL 6 DAY AND  CURDATE() + INTERVAL 1 DAY 
                             ';
                             
                    $dashboard = DB::select($query,['COMPLETED', 'COMPLETED', 'COMPLETED']);

                     return $dashboard;

                case 'This month':
                   
                              $query ="SELECT (SELECT SUM(grand_total) 
                            FROM sales WHERE status = ? AND created_at BETWEEN DATE_FORMAT(CURDATE(),'%Y-%m-01') AND  DATE_FORMAT(CURDATE() + INTERVAL 1  MONTH,'%Y-%m-01'))AS grandTotal, 
                            COALESCE(SUM((si.sell_price - si.buy_price)*si.quantity),0) AS netProfit,
                             (SELECT COUNT(*) FROM sales WHERE status = ? AND created_at  BETWEEN DATE_FORMAT(CURDATE(),'%Y-%m-01') AND  DATE_FORMAT(CURDATE() + INTERVAL 1  MONTH,'%Y-%m-01') ) AS transactions, 
                             (SELECT COALESCE(AVG(grand_total),0) FROM sales WHERE created_at  BETWEEN DATE_FORMAT(CURDATE(),'%Y-%m-01') AND  DATE_FORMAT(CURDATE() + INTERVAL 1  MONTH,'%Y-%m-01')) 
                             AS avg_receipt 
                             FROM sales s 
                             LEFT JOIN sale_items si ON s.id = si.sale_id
                             WHERE s.status = ? AND s.created_at  BETWEEN DATE_FORMAT(CURDATE(),'%Y-%m-01') AND  DATE_FORMAT(CURDATE() + INTERVAL 1  MONTH,'%Y-%m-01') 
                             ";
                     $dashboard = DB::select($query,['COMPLETED', 'COMPLETED', 'COMPLETED']);

                   return $dashboard;

                case 'Today':
                     $query ='SELECT (SELECT SUM(grand_total) 
                            FROM sales WHERE status = ? AND created_at BETWEEN CURDATE() AND  CURDATE() + INTERVAL 1 DAY )AS grandTotal, 
                            COALESCE(SUM((si.sell_price - si.buy_price)*si.quantity),0) AS netProfit,
                             (SELECT COUNT(*)  FROM sales WHERE status = ? AND created_at BETWEEN CURDATE() AND  CURDATE() + INTERVAL 1 DAY ) AS transactions, 
                             (SELECT COALESCE(AVG(grand_total),0) FROM sales WHERE  created_at BETWEEN CURDATE() AND  CURDATE() + INTERVAL 1 DAY) 
                             AS avg_receipt 
                             FROM sales s 
                             LEFT JOIN sale_items si ON s.id = si.sale_id
                             WHERE s.status = ? AND s.created_at BETWEEN CURDATE() AND  CURDATE() + INTERVAL 1 DAY 
                             ';
                    $dashboard = DB::select($query,['COMPLETED', 'COMPLETED', 'COMPLETED']);

                    return $dashboard;
            }
          }


          if(!empty($startDate) && !empty($endDate) && $filter == 'custom_date'){
            $query ="SELECT (SELECT SUM(grand_total) 
                            FROM sales WHERE status = ? AND created_at BETWEEN ? AND ?)AS grandTotal, 
                            COALESCE(SUM((si.sell_price - si.buy_price)*si.quantity),0) AS netProfit,
                             (SELECT COUNT(*) FROM sales WHERE status = ? AND created_at  BETWEEN ? AND ? ) AS transactions, 
                             (SELECT COALESCE(AVG(grand_total),0) FROM sales WHERE created_at  BETWEEN ? AND ?) 
                             AS avg_receipt 
                             FROM sales s 
                             LEFT JOIN sale_items si ON s.id = si.sale_id
                             WHERE s.status = ? AND s.created_at BETWEEN ? AND ?
                             ";

            $dashboard = DB::select($query, [
                'COMPLETED', $startDate, $endDate,
                'COMPLETED', $startDate, $endDate,
                $startDate, $endDate,
                'COMPLETED', $startDate, $endDate,
            ]);

            return $dashboard;
          }


         
           
    }
    public function getBestSelling(){

        $query = "SELECT
                p.name,
                p.unit,
                COALESCE(SUM(s.quantity), 0) AS sold,
                COALESCE(SUM((s.sell_price - s.buy_price) * s.quantity), 0) AS profit
                FROM products p
                LEFT JOIN sale_items s ON p.id = s.product_id
                GROUP BY p.id, p.name, p.unit
                ORDER BY profit DESC
                LIMIT 5";

                return DB::select($query);


    }

    public function getLowStock(){
        $query = "SELECT
                    name,
                    stock_quantity,
                    min_stock
                    FROM products
                    WHERE stock_quantity = 0 OR stock_quantity < min_stock";

                    return DB::select($query);

    }

    public function getChart($filter, $startDate, $endDate){
        if(empty($filter) ){
            return [
                'status' => false,
                'message' => 'data kosong',
            ];
          }

          if(empty($startDate && $endDate) && $filter != 'custom date'){
            switch ($filter) {
                case 'ALL':
                    $query = 'SELECT 
                                date(created_at) AS time,
                                 SUM(grand_total) AS sales
                                 FROM sales
                                 GROUP BY date(created_at)
                                 ORDER BY time ASC
                            ';
                    $chart = DB::select($query);

                    return $chart;
                    

                case 'weekly':
                   $query ='SELECT 
                                date(created_at) AS time,
                                 SUM(grand_total) AS sales
                                 FROM sales
                                 WHERE created_at BETWEEN CURDATE() - INTERVAL 6 DAY AND CURDATE() + INTERVAL 1 DAY
                                 GROUP BY date(created_at)
                                 ORDER BY time ASC
                            ';;
                             
                    $dashboard = DB::select($query);

                     return $dashboard;

                case 'This month':
                   
                              $query ="SELECT 
                                date(created_at) AS time,
                                 SUM(grand_total) AS sales
                                 FROM sales
                                 WHERE created_at BETWEEN DATE_FORMAT(CURDATE(),'%Y-%m-01') AND  DATE_FORMAT(CURDATE() + INTERVAL 1  MONTH,'%Y-%m-01')
                                 GROUP BY date(created_at)
                                 ORDER BY time ASC
                            ";
                             
                     $dashboard = DB::select($query);

                   return $dashboard;

                case 'Today': 
                     $query ="SELECT 
                                date(created_at) AS time,
                                 SUM(grand_total) AS sales
                                 FROM sales
                                 WHERE created_at BETWEEN CURDATE() AND  CURDATE() + INTERVAL 1 DAY
                                 GROUP BY date(created_at)
                                 ORDER BY time ASC
                            ";
                    $dashboard = DB::select($query);

                    return $dashboard;
            }
          }


          if(!empty($startDate) && !empty($endDate) && $filter == 'custom_date'){
            $query ="SELECT 
                                date(created_at) AS time,
                                 SUM(grand_total) AS sales
                                 FROM sales
                                 WHERE created_at BETWEEN :startDate AND  :endDate
                                 GROUP BY date(created_at)
                                 ORDER BY time ASC
                            ";

            $dashboard = DB::select($query,['startDate'=>$startDate,'endDate'=>$endDate]);

            return $dashboard;

    }
    }
}
