<?php
/**
 * Claim handler
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

use Xpressengine\Counter\Counter;
use Xpressengine\Config\ConfigManager;
use Xpressengine\User\UserHandler as User;
use Xpressengine\User\UserInterface;


/**
 * Claim handler
 *
 * @category    Claim
 * @package     Claim
 * @author      XE Team (akasima) <osh@xpressengine.com>
 * @copyright   2014 Copyright (C) NAVER <http://www.navercorp.com>
 * @license     http://www.gnu.org/licenses/lgpl-3.0-standalone.html LGPL
 * @link        http://www.xpressengine.com
 */
class Handler
{
    /**
     * @var ClaimRepository
     */
    protected $repo;

    /**
     * @var ConfigManager
     */
    protected $configManager;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var string
     */
    protected $claimType;

    /**
     * @var string
     */
    const CONFIG_NAME = 'Claim';

    /**
     * create instance
     *
     * @param ClaimRepository $repo          repository
     * @param ConfigManager   $configManager config manager
     * @param User            $user          member
     */
    public function __construct(ClaimRepository $repo, ConfigManager $configManager, User $user)
    {
        $this->repo = $repo;
        $this->configManager = $configManager;
        $this->user = $user;
    }

    /**
     * claim 에서 사용 할 type name 설정
     *
     * @param string $claimType claim type
     * @return void
     */
    public function set($claimType)
    {
        $this->claimType = $claimType;
    }

    /**
     * 신고 수
     *
     * @param string $targetId targetId
     * @return int
     */
    public function count($targetId)
    {
        return $this->repo->count($this->claimType, $targetId);
    }

    /**
     * 신고 추가
     *
     * @param string        $targetId targetId
     * @param UserInterface $author   user instance
     * @param string        $shortCut 바로가기
     * @return void
     */
    public function add($targetId, UserInterface $author, $shortCut)
    {
        $args =[
            'claimType' => $this->claimType,
            'shortCut' => $shortCut,
            'targetId' => $targetId,
            'userId' => $author->getId(),
            'createdAt' => date('Y-m-d H:i:s'),
            'ipaddress' => $_SERVER['REMOTE_ADDR'],
        ];


        if ($this->invoked($targetId, $author) === true) {
            throw new Exceptions\InvokedException;
        }
        $this->repo->insert($args);
    }

    /**
     * 신고 삭제
     *
     * @param int $id id
     * @return void
     */
    public function remove($id)
    {
        $this->repo->delete($id);
    }

    /**
     * 신고 삭제
     *
     * @param string        $targetId targetId
     * @param UserInterface $author   user instance
     * @return void
     */
    public function removeByTargetId($targetId, UserInterface $author)
    {
        $this->repo->deleteByUserId($author->getId(), $this->claimType, $targetId);
    }

    /**
     * 신고 여부
     *
     * @param string        $targetId targetId
     * @param UserInterface $author   user instance
     * @return bool
     */
    public function invoked($targetId, UserInterface $author)
    {

        return $this->repo->fetchByUserId($author->getId(), $this->claimType, $targetId) !== null;
    }

    /**
     * get paginate
     * 회원정보를 추가해서 반환
     *
     * @param array $wheres  make where query list
     * @param array $orders  make order query list
     * @param int   $perPage count of per page
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function paginate(array $wheres = [], array $orders = [], $perPage = 20)
    {
        $paginate = $this->repo->paginate($wheres, $orders, $perPage);

        $userIds = [];
        foreach ($paginate as $item) {
            if (in_array($item['userId'], $userIds) === false) {
                $userIds[] = $item['userId'];
            }
        }

        $users = $this->user->users()->find($userIds);
        $usersByUserId = [];
        foreach ($users as $user) {
            $usersByUserId[$user->id] = $user;
        }

        foreach ($paginate as $key => $item) {
            $item['user'] = $usersByUserId[$item['userId']];
            $paginate[$key] = $item;
        }

        return $paginate;
    }
}
