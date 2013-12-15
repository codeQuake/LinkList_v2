<?php
namespace linklist\system\attachment;
use wcf\system\WCF;
use wcf\util\ArrayUtil;
use linklist\data\link\Link;
use wcf\system\attachment\AbstractAttachmentObjectType;

class LinkAttachmentObjectType extends AbstractAttachmentObjectType {

    public function getMaxSize() {
            return WCF::getSession()->getPermission('user.linklist.link.attachmentMaxSize');
    }
    
    public function getAllowedExtensions() {
        return ArrayUtil::trim(explode("\n", WCF::getSession()->getPermission('user.linklist.link.allowedAttachmentExtensions')));
    }
    
    public function getMaxCount() {
        return WCF::getSession()->getPermission('user.linklist.link.maxAttachmentCount');
    }
    
    public function canDownload($objectID) {
	    return WCF::getSession()->getPermission('user.linklist.link.canDownloadAttachments');
    }
    
    public function canViewPreview($objectID) {
		return WCF::getSession()->getPermission('user.linklist.link.canDownloadAttachments');
	}
    
    public function canUpload($objectID, $parentObjectID = 0) {		
		return WCF::getSession()->getPermission('user.linklist.link.canUploadAttachment');
	}
    
    public function canDelete($objectID) {
        return WCF::getSession()->getPermission('user.linklist.link.canUploadAttachment');
	}
}