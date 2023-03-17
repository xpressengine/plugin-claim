<?php

namespace Xpressengine\Plugins\Claim;

use App\Facades\XeDB;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Xpressengine\Plugins\Claim\Factory\ClaimFactory;
use Xpressengine\User\UserInterface;
use Xpressengine\Config\ConfigManager;
use Xpressengine\Plugins\Claim\Models\ClaimLog;
use Xpressengine\Plugins\Claim\Repositories\ClaimRepository;

class ClaimHandler
{
    /** @var ClaimRepository */
    protected $repository;

    /**
     * @var ConfigManager
     */
    protected $configManager;

    /**
     * @var string
     */
    const CONFIG_NAME = 'Claim';

    /**
     * @param ClaimRepository $repository
     * @param ConfigManager $configManager
     */
    public function __construct(ClaimRepository $repository, ConfigManager $configManager)
    {
        $this->repository = $repository;
        $this->configManager = $configManager;
    }

    /**
     * 해당 ID의 신고 로그를 반환
     * @param string $id
     * @return ClaimLog
     */
    public function findOrFail(string $id)
    {
        return $this->repository->findOrFail($id);
    }

    /**
     * 해당 컨텐츠가 신고된 횟수를 반환
     * @param string $claimType
     * @param string $targetId
     * @return int
     */
    public function count(string $claimType, string $targetId)
    {
        return $this->repository->where('target_id', $targetId)
            ->where('claim_type', $claimType)
            ->count();
    }

    /**
     * 신고 로그 삭제
     * @param string $id
     * @return bool|null
     * @throws \Exception
     */
    public function delete(string $id)
    {
        return $this->repository->findOrFail($id)->delete();
    }

    /**
     * 신고 로그 삭제
     * @param string $claimType
     * @param string $targetId
     * @param UserInterface $author
     * @return mixed
     */
    public function deleteByTargetId(string $claimType, string $targetId, UserInterface $author)
    {
        return $this->repository->where('user_id', $author->getKey())
            ->where('target_id', $targetId)
            ->where('claim_type', $claimType)
            ->delete();
    }

    /**
     * 대상을 신고
     * @param string $claimType
     * @param string $targetId
     * @param string $targetUserId
     * @param UserInterface $author
     * @param string $shortCut
     * @param string $message
     * @return void
     */
    public function report(
        string $claimType,
        string $targetId,
        string $targetUserId,
        UserInterface $author,
        string $shortCut,
        string $message = ''
    ) {
        if ($this->exists($claimType, $author, $targetId) === true) {
            throw new Exceptions\AlreadyClaimedException;
        }

        return $this->repository->create([
            'claim_type' => $claimType,
            'short_cut' => $shortCut,
            'target_id' => $targetId,
            'target_user_id' => $targetUserId,
            'user_id' => $author->getKey(),
            'message' => $message,
            'status' => ClaimLog::STATUS_NEW,
            'ipaddress' => $_SERVER['REMOTE_ADDR'],
        ]);
    }

    /**
     * 신고 여부
     * @param string $claimType
     * @param UserInterface $author
     * @param string $targetId
     * @return bool
     */
    public function exists(string $claimType, UserInterface $author, string $targetId)
    {
        return $this->repository->where('user_id', $author->getKey())
            ->where('target_id', $targetId)
            ->where('claim_type', $claimType)
            ->exists();
    }

    /**
     * 신고 로그 수정
     * @param string $id
     * @param string $status
     * @param string $adminMessage
     * @return ClaimLog
     */
    public function updateLog(string $id, string $status, string $adminMessage = '')
    {
        $log = $this->findOrFail($id);

        if (!array_key_exists($status, ClaimLog::STATUSES)) {
            throw new \UnexpectedValueException('Invalid Claim status');
        }

        $log->status = $status;
        $log->admin_message = $adminMessage;
        $log->save();

        return $log;
    }

    /**
     * 신고 로그 페이지네이션 반환
     * @param array $inputs
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginateClaimLogs(array $inputs = [])
    {
        $query = $this->repository->query();
        $this->applyFiltersToClaimLogs($query, $inputs);
        $logs = $query->paginate();

        /** @var ClaimFactory $claimFactory */
        $claimFactory = app(ClaimFactory::class);
        $logs->setCollection($logs->getCollection()->map(function ($log) use ($claimFactory) {
            $log->claim_type_text = xe_trans($claimFactory->make($log->claim_type)->getText());
            return $log;
        }));

        return $logs;
    }

    /**
     * 신고 로그 검색 필터 적용
     * @param Builder $query
     * @param array $inputs
     * @return Builder
     */
    protected function applyFiltersToClaimLogs(Builder $query, array $inputs = [])
    {
        $claimType = array_get($inputs, 'claim_type');
        if ($claimType !== null) {
            $query->where('claim_type', $claimType);
        }

        $claimStatus = array_get($inputs, 'claim_status');
        if ($claimStatus !== null) {
            $query->where('status', $claimStatus);
        }

        $keyField = array_get($inputs, 'keyfield');
        $keyword = array_get($inputs, 'keyword');
        if ($keyField !== null && $keyword !== null) {
            if ($keyField === 'author_name') {
                $query->whereHas('user', function ($query) use ($keyword) {
                    $query->where('display_name', 'like', "%{$keyword}%");
                });
            }

            else if ($keyField === 'target_name') {
                $query->whereHas('targetUser', function ($query) use ($keyword) {
                    $query->where('display_name', 'like', "%{$keyword}%");
                });
            }

            else if ($keyField === 'message') {
                $query->where('message', 'like', "%{$keyword}%");
            }
        }

        $startDate = array_get($inputs, 'start_date');
        $endDate = array_get($inputs, 'end_date');
        if ($startDate !== null || $endDate !== null) {
            $query->whereBetween('created_at', [
                $startDate ? $startDate . ' 00:00:00' : Carbon::minValue(),
                $endDate ? $endDate . ' 23:59:59' : Carbon::maxValue()
            ]);
        }

        $query->orderByDesc('created_at');

        return $query;
    }
}
