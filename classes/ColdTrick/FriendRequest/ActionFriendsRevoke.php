<?php

namespace ColdTrick\FriendRequest;

use ElggUser;
use Elgg\BadRequestException;
use Elgg\Http\ResponseBuilder;
use Elgg\Request;
use Exception;

class ActionFriendsRevoke {

	public function __invoke(Request $request) {

		$friend_guid = (int) $request->getParam('friend_guid');
		$user_guid = (int) $request->getParam('user_guid');
		
		$friend = get_user($friend_guid);
		$user = get_user($user_guid);
		
		if (!$friend instanceof ElggUser || !$user instanceof ElggUser) {
			return;
		}
		
		// remove friend from user
		try {
			remove_entity_relationship($user->guid, 'friendrequest', $friend->guid);
			
			return elgg_ok_response('', elgg_echo('friend_request:revoke:success'));
			
		} catch (Exception $e) {
			return elgg_error_response(
				elgg_echo('friend_request:revoke:fail', [$friend->getDisplayName()]),
				REFERER,
				$e->getCode() ? : ELGG_HTTP_INTERNAL_SERVER_ERROR
			);
		}

	}
}
