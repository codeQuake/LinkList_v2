new LINKLIST.Link.Preview();
{if $__linklist->isActiveApplication()}
{if MODULE_LIKE && $__wcf->getSession()->getPermission('user.like.canViewLike') && LINKLIST_ENABLE_LIKES}new LINKLIST.Link.Like({if $__wcf->getUser()->userID && $__wcf->getSession()->getPermission('user.like.canLike')}1{else}0{/if}, {@LIKE_ENABLE_DISLIKE}, {@LIKE_SHOW_SUMMARY}, {@LIKE_ALLOW_FOR_OWN_CONTENT});{/if}
{/if}

