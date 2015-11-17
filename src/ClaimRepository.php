<?php
/**
 * Claim module repository
 *
 * PHP version 5
 *
 * @category    Claim
 * @package     Claim
 * @author      XE Team (akasima) <osh@xpressengine.com>
 * @copyright   2014 Copyright (C) NAVER <http://www.navercorp.com>
 * @license     http://www.gnu.org/licenses/lgpl-3.0-standalone.html LGPL
 * @link        http://www.xpressengine.com
 */
namespace Xpressengine\Plugins\Claim;

use Xpressengine\Database\VirtualConnectionInterface;
use Xpressengine\Database\DynamicQuery;

/**
 * Claim module repository
 *
 * @category    Claim
 * @package     Claim
 * @author      XE Team (akasima) <osh@xpressengine.com>
 * @copyright   2014 Copyright (C) NAVER <http://www.navercorp.com>
 * @license     http://www.gnu.org/licenses/lgpl-3.0-standalone.html LGPL
 * @link        http://www.xpressengine.com
 */
class ClaimRepository
{

    /**
     * @var string
     */
    protected $table = 'claim_log';

    /**
     * @var VirtualConnectionInterface
     */
    protected $conn;

    /**
     * create instance
     *
     * @param VirtualConnectionInterface $conn database connection
     */
    public function __construct(VirtualConnectionInterface $conn)
    {
        $this->conn = $conn;
    }

    /**
     * insert claim log
     *
     * @param array $args insert arguments
     * @return void
     */
    public function insert(array $args)
    {
        $this->conn->table($this->table)->insert([
            'claimType' => $args['claimType'],
            'shortCut' => $args['shortCut'],
            'targetId' => $args['targetId'],
            'userId' => $args['userId'],
            'createdAt' => $args['createdAt'],
            'ipaddress' => $args['ipaddress'],
        ]);
    }

    /**
     * 삭제
     *
     * @param int $id id
     * @return void
     */
    public function delete($id)
    {
        $this->conn->table($this->table)->where('id', $id)->delete();
    }

    /**
     * user id 를 이용한 삭제
     *
     * @param string $userId    아아디
     * @param string $claimType 신고 타입
     * @param string $targetId  대상 id
     * @return void
     */
    public function deleteByUserId($userId, $claimType, $targetId)
    {
        $this->conn->table($this->table)->where('userId', $userId)
        ->where('claimType', $claimType)->where('targetId', $targetId)->delete();
    }

    /**
     * get claim log count
     *
     * @param string $claimType 신고 타입
     * @param string $targetId  대상 id
     * @return int
     */
    public function count($claimType, $targetId)
    {
        $wheres = [
            'targetId' => $targetId,
            'claimType' => $claimType,
        ];

        $query = $this->conn->table($this->table);
        $query = $this->wheres($query, $wheres);
        return $query->count();
    }

    /**
     * get claim log
     *
     * @param array $wheres make where query list
     * @return array
     */
    public function fetch($wheres)
    {
        $query = $this->conn->table($this->table);
        $query = $this->wheres($query, $wheres);
        return $query->frist();
    }

    /**
     * get Claim log by userId
     *
     * @param string $userId    아아디
     * @param string $claimType 신고 타입
     * @param string $targetId  대상 id
     * @param array  $columns   get columns list
     * @return array
     */
    public function fetchByUserId($userId, $claimType, $targetId, array $columns = ['*'])
    {
        return $this->conn->table($this->table)->where([
            'targetId' => $targetId,
            'userId' => $userId,
            'claimType' => $claimType,
        ])->first($columns);
    }

    /**
     * $wheres parameter 로 query 반환
     *
     * @param DynamicQuery $query  query builder
     * @param array        $wheres make where query list
     * @return DynamicQuery
     */
    public function wheres(DynamicQuery $query, array $wheres)
    {
        if (isset($wheres['id'])) {
            $query = $query->where('id', '=', $wheres['id']);
        }

        if (isset($wheres['userId'])) {
            $query = $query->where('userId', '=', $wheres['userId']);
        }

        if (isset($wheres['targetId'])) {
            $query = $query->where('targetId', '=', $wheres['targetId']);
        }

        if (isset($wheres['ClaimType'])) {
            $query = $query->where('ClaimType', '=', $wheres['ClaimType']);
        }
        return $query;
    }

    /**
     * $orders parameter로 query를 만든다
     *
     * @param DynamicQuery $query  query builder
     * @param array        $orders make order query list
     * @return DynamicQuery
     * @todo $callback을 어떻게 사용 할 것이지..
     */
    public function orders(DynamicQuery $query, array $orders)
    {
        // set default
        if (count($orders) == 0) {
            $orders['createdAt'] = 'desc';
        }

        if (isset($orders['createdAt'])) {
            $query = $query->orderBy('createdAt', $orders['createdAt']);
        }
        return $query;
    }

    /**
     * get paginator
     *
     * @param array $wheres  make where query list
     * @param array $orders  make order query list
     * @param int   $perPage count of per page
     * @param array $columns get columns list
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function paginate(array $wheres, array $orders, $perPage, array $columns = ['*'])
    {
        $query = $this->conn->table($this->table);
        $query = $this->wheres($query, $wheres);
        $query = $this->orders($query, $orders);
        return $query->paginate($perPage, $columns);
    }
}
