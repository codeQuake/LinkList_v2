new LINKLIST.Link.Preview();
{if MODULE_LIKE && $__wcf->getSession()->getPermission('user.like.canViewLike')}new LINKLIST.Link.Like({if $__wcf->getUser()->userID && $__wcf->getSession()->getPermission('user.like.canLike')}1{else}0{/if}, {@LIKE_ENABLE_DISLIKE}, {@LIKE_SHOW_SUMMARY}, {@LIKE_ALLOW_FOR_OWN_CONTENT});{/if}
