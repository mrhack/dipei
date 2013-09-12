<?php
/**
 * User: wangfeng
 * Date: 13-8-7
 * Time: 下午6:43
 */
require_once  __DIR__.'/../DipeiTestCase.php';

class PostControllerTest extends DipeiTestCase
{
    public function testAdd()
    {
        $this->dataSet->setUpTestUser();
        $request=new Test_Http_Request();
        $request->setRequestUri('/post/add');
        $request->method = 'POST';
        $input=array(
            'type'=>Constants::FEED_TYPE_POST,
            'lid'=>11,
            'title'=>'testPost',
            'content'=>'[{"tag":"p","child":[{"tag":"img","attr":{"src":"http://www.lepei.cc/public/img/1000/507_1267-880.jpg","_src":"http://www.lepei.cc/public/img/1000/507_1267-880.jpg","width":"1142","height":"825"}}]},{"tag":"p","child":[{"text":"sdakfjaslkdfj失节事大!!"},{"tag":"em"}]}]'
        );
        $input['content']=str_replace('www.lepei.cc',HOST,$input['content']);
        $request->setPost($input);

        $this->getYaf()->getDispatcher()->dispatch($request);
        $this->assertAjaxCode(Constants::CODE_SUCCESS);
        //assert added
        $post = PostModel::getInstance()->fetchOne();
        unset($input['content']);//escape rich content
        $this->assertArrayEquals($input, PostModel::getInstance()->format($post));
        $this->assertEquals(1, $post['uid']);
        $this->assertEquals(Constants::STATUS_NEW, $post['s']);
        $this->assertEquals(array('/1000/507_1267-880.jpg'),$post['ims']);
        //assert feed
        $feed = FeedModel::getInstance()->fetchOne(array('oid' => 1, 'lpt' => 11, 'tp' => Constants::FEED_TYPE_POST));
        $this->assertNotEmpty($feed);
        $this->assertEquals(array(13, 11), $feed['lpt']);
        return $post['_id'];
    }

    /**
     * @depends testAdd
     */
    public function testAddReply($pid)
    {
        $request=new Test_Http_Request();
        $request->method = 'POST';
        $input=array(
            'pid'=>$pid,
            'type'=>Constants::FEED_TYPE_POST,
            'content'=>'test content'
        );
        $request->setRequestUri('post/addReply');
        $request->setPost($input);

        $this->getYaf()->getDispatcher()->dispatch($request);
        $this->assertAjaxCode(Constants::CODE_SUCCESS);

        $reply = ReplyModel::getInstance()->fetchOne();
        $this->assertArrayEquals($input, ReplyModel::getInstance()->format($reply));
        $this->assertEquals(1, $reply['uid']);
        $this->assertEquals(Constants::STATUS_NEW, $reply['s']);
        return $pid;
    }

    /**
     * @depends testAddReply
     */
    public function testAddReplyReply($pid)
    {
        $request=new Test_Http_Request();
        $request->method = 'POST';
        $request->setRequestUri('post/addReply');
        $input=array(
            'pid'=>$pid,
            'rid'=>1,
            'type'=>Constants::FEED_TYPE_POST,
            'content'=>'reply reply content'
        );
        $request->setPost($input);

        $this->getYaf()->getDispatcher()->dispatch($request);
        $this->assertAjaxCode(Constants::CODE_SUCCESS);

        $reply = ReplyModel::getInstance()->fetchOne(array('rid'=>1,'pid'=>$pid));
        $this->assertArrayEquals($input, ReplyModel::getInstance()->format($reply));
        return $pid;
    }

    public function testRemoveReply()
    {
        $request=new Test_Http_Request();
        $request->method = 'POST';
        $request->setRequestUri('post/removeReply');
        $input=array(
            'id'=>1,
        );
        $request->setRequest($input);

        $this->getYaf()->getDispatcher()->dispatch($request);
        $this->assertAjaxCode(Constants::CODE_SUCCESS);

        $reply = ReplyModel::getInstance()->fetchOne(array('_id' => 1));
        $this->assertEquals(Constants::STATUS_DELETE, $reply['s']);
    }

    /**
     * @depends testAddReplyReply
     */
    public function testRemove($pid)
    {
        $request=new Test_Http_Request();
        $request->setRequestUri('/post/remove');
        $request->setRequest(array('id' => $pid));
        $this->getYaf()->getDispatcher()->dispatch($request);
        $this->assertAjaxCode(Constants::CODE_SUCCESS);
        $post=PostModel::getInstance()->fetchOne(array('_id' => $pid));
        $this->assertEquals(Constants::STATUS_DELETE, $post['s']);
    }
}
