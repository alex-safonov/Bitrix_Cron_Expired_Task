/*********EXPIRED TASK*********/

$tasks = \CIBlockElement::GetList([],['IBLOCK_ID'=>Constant::TASKS_IBLOCK_ID, 'PROPERTY_STATUS'=>Constant::TaskStatusID('PUBLISHED'),'<PROPERTY_DATE_OF_THE_ASSIGNMENT'=>ConvertDateTime(date("d.m.Y"), "YYYY-MM-DD")]);
while ($task = $tasks->GetNextElement()) {
    $f = $task->GetFields();
    $p = $task->GetProperties();

    \CIBlockElement::SetPropertyValuesEx($f['ID'],Constant::TASKS_IBLOCK_ID, [
        "STATUS" => Constant::TaskStatusID('EXPIRED'),
        'REFUSAL_REASON' => 'Истечение срока сдачи для задачи в статусе Опубликована'
    ]);

    $fundUser = FundUser::GetDataByIbElemID($p['FUND_USER']['VALUE']);

    \CEvent::SendImmediate('EXPIRED_TASK', "s1", [
        'EMAIL_TO' => $fundUser['PROPS']['EMAIL']['VALUE'],
        'TASK_NAME' => $f['NAME'],
        'FUND_USER' => $fundUser['PROPS']['NAME']['VALUE'],
        'TASK_LINK' => '/foundations/lk/tasks/detail.php?ID='.$f['ID']
    ]);

}


/***********************/
/*********DELETE EXPIRED TASK*********/
$tasks = \CIBlockElement::GetList([],['IBLOCK_ID'=>Constant::TASKS_IBLOCK_ID, 'PROPERTY_STATUS'=>Constant::TaskStatusID('EXPIRED'),'<PROPERTY_DATE_OF_THE_ASSIGNMENT'=>ConvertDateTime(date("d.m.Y", strtotime("-3 month")), "YYYY-MM-DD")]);
while ($task = $tasks->GetNextElement()) {
    $fg = $task->GetFields();
    $pg = $task->GetProperties();

    \CIBlockElement::SetPropertyValuesEx($fg['ID'],Constant::TASKS_IBLOCK_ID, [
        "STATUS" => Constant::TaskStatusID('DELETED'),
        'REFUSAL_REASON' => 'Истечение срока сдачи для задачи в статусе Просрочена'
    ]);

    $fundUser = FundUser::GetDataByIbElemID($pg['FUND_USER']['VALUE']);

    \CEvent::SendImmediate('EXPIRED_TASK', "s1", [
        'EMAIL_TO' => $fundUser['PROPS']['EMAIL']['VALUE'],
        'TASK_NAME' => $fg['NAME'],
        'FUND_USER' => $fundUser['PROPS']['NAME']['VALUE'],
        'TASK_LINK' => '/foundations/lk/tasks/detail.php?ID='.$fg['ID']
    ]);

}
