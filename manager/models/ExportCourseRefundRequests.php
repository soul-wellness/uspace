<?php

class ExportCourseRefundRequests extends Export
{

    public function __construct(int $langId, int $exportId = 0)
    {
        $this->type = static::COURSE_REFUND_REQUESTS;
        parent::__construct($langId, $exportId);
    }

    public function getFields(): array
    {
        $this->headers = [
            'course_title' => Label::getLabel('LBL_COURSE_NAME'),
            'teacher_name' => Label::getLabel('LBL_LEARNER_NAME'),
            'corere_status' => Label::getLabel('LBL_STATUS'),
            'corere_created' => Label::getLabel('LBL_REQUESTED_ON'),
        ];
        return [
            'course_title', 'corere_status', 'corere_created',
            'CONCAT(user_first_name, " ", user_last_name) as teacher_name'
        ];
    }

    public function writeData($fh, $rs): int
    {
        $count = 0;
        $cats = static::getCategories();
        fputcsv($fh, array_values($this->headers));
        while ($row = FatApp::getDb()->fetch($rs)) {
            fputcsv($fh, [
                'course_title' => $row['course_title'],
                'teacher_name' => $row['teacher_name'],
                'corere_status' => Course::getRefundStatuses($row['corere_status']),
                'corere_created' => MyDate::formatDate($row['corere_created']),
            ]);
            $count++;
        }
        return $count;
    }

    public static function getCategories()
    {
        $srch = new SearchBased(Category::DB_TBL, 'cate');
        $srch->joinTable(Category::DB_LANG_TBL, 'LEFT JOIN', 'cate.cate_id = catelang.catelang_cate_id '
                . ' AND catelang.catelang_lang_id = ' . MyUtility::getSiteLangId(), 'catelang');
        $srch->addMultipleFields(['cate_id', 'IFNULL(cate_name, cate_identifier) AS cate_name']);
        $srch->addCondition('cate_type', '=', Category::TYPE_COURSE);
        $srch->addDirectCondition('cate_deleted IS NULL');
        $srch->addOrder('cate_order');
        $srch->doNotCalculateRecords();
        $srch->doNotLimitRecords();
        return FatApp::getDb()->fetchAllAssoc($srch->getResultSet());
    }

}
