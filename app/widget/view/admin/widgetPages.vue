<style>
	
</style>
<template>
<div>
    <header class="mipcms-container-header clearfix">
        <div class="header-group">
            <h4 class="title">功能</h4> <h5 class="sub-title">单页面</h5>
        </div>
    	<button class="ivu-btn ivu-btn-primary ivu-btn-circle pull-right" @click='itemAdd'><i class="ivu-icon ivu-icon-edit"></i> 添加页面</button>
    </header>
    <main class="mipcms-container-body" style="height: calc(100% - 50px)">
        <section class="mip-box">
            <section class="mip-box-body">
                <!--内容列表-->
                <section class="diy-table-list" v-cloak>
                    
                    <div class="diy-table-item diy-table-item-header">
                        <ul class="list-unstyled row">
                            <li class="col-md-1">
                                <span>标题</span>
                            </li>
                            <li class="col-md-3">
                                <span>URL地址</span>
                            </li>
                            <li class="col-md-2">
                                <span>关键词</span>
                            </li>
                            <li class="col-md-2">
                                <span>描述</span>
                            </li>
                            <li class="col-md-2">
                                <span>操作</span>
                            </li>
                        </ul>
                    </div>
                    <div v-if='itemList.length' class="diy-table-body">
                        <div class="diy-table-item" v-for='item in itemList' >
                             <ul class="list-unstyled row">
                                    
                                <li class="col-md-1 over-h-e">
                                    <span>{{item.title}}</span>
                                </li>
                                <li class="col-md-3">
                                    <span>{{item.url}}</span>
                                </li>
                                <li class="col-md-2 over-h-e">
                                    <span>{{item.keywords}}</span>
                                </li>
                                <li class="col-md-2 over-h-e">
                                    <span>{{item.description}}</span>
                                </li>
                                <li class="col-md-2">
                                    <i-button type="error" size="small" @click='itemDel(item)'>删除</i-button>
                                    <i-button type="primary" size="small" @click='itemEdit(item)'>编辑</i-button>
                                </li>
                             
                            </ul>
                        </div>
                    </div>
                    <div class="no-block" v-else>
                        <Icon type="ios-filing-outline"></Icon>
                        <p>暂无数据</p>
                    </div>
                </section>
                <!--内容列表结束-->
            </section>
        </section>
     <Modal :title="dialogItemTitle" v-model='dialogItemStatus' v-cloak width='1000' :mask-closable='false'>
            <i-form :model="item" :rules="itemRules" ref="item" :label-width="80" >
    
                <Form-Item label="{$itemName}标题" prop="title">
                    <i-input v-model="item.title" placeholder="例：关于我们"></i-input>
                </Form-Item>
                <Form-Item label="{$itemName}地址" prop="url_name">
                    <i-input v-model="item.url_name" placeholder="例：about" style="width:400px">
                        <span slot="prepend">{$domain}/</span>
                        <span slot="append">.html</span>
                    </i-input>
                </Form-Item>
                <Form-Item label="关键词">
                    <i-input type="textarea" v-model="item.keywords"></i-input>
                </Form-Item>
                <Form-Item label="描述">
                    <i-input type="textarea" v-model="item.description"></i-input>
                </Form-Item>
                
                <Form-Item label="内容">
                    <textarea id="article_editor" class="simditor" style="height: 400px;" placeholder="请输入内容..."></textarea>
                </Form-Item>
                
            </i-form>
            <div slot="footer" class="dialog-footer">
                <i-button @click="dialogItemStatus = false">取 消</i-button>
                <i-button type="primary" @click="itemPost('item')">确 定</i-button>
            </div>
        </Modal>
</div>
</template>

<script>
    export default {
     data () {
       return {
       	 	itemName: '{$itemName}',
            itemList: [], 
            
            item: {
                id: '',
                title: '',
                url_name: '',
                template: 'page',
                content: '',
                description: '',
                keywords: '',
            },
            content: '',
            
            templateList: [],
            dialogItemTitle: '添加{$itemName}',
            dialogItemStatus: false,
            
            itemRules: {
                title: [{
                    required: true,
                    message: '请输入名称',
                    trigger: 'blur'
                }],
                url_name: [{
                    required: true,
                    message: '请输入别名',
                    trigger: 'blur'
                }],
            },
            
            pagination: {
                currentPage: 1,
                limit: 10,
                total: this.total,
            },
       }
     },
     watch: {
            
        },
        mounted() {
            this.getItemList();
            
            this.editorInit();
        },
        methods: {
            editorInit() {
                var _this = this;
                setTimeout(function() {
                    _this.createEditor();
                }, 100);
            },
             //创建编辑器
            createEditor() {
                var _this = this;
                
                document.getElementById('article_editor').innerHTML = '';
                this.editor = new Simditor({
                    textarea: document.getElementById('article_editor'),
                    toolbar: _this.toolbar,
                    upload: {
                        url: '{$domain}/setting/ApiAdminUpload/defaultImgUpload',
                        params: {
                            type: 'article',
                        },
                        fileKey: 'fileDataFileName',
                        connectionCount: 3,
                        leaveConfirm: '正在上传文件'
                    },
                    pasteImage: true,
                    autosave: 'editor-content'
                });
            },
            itemAdd() {
                this.item.id = '';
                this.dialogItemTitle = '添加{$itemName}';
                this.item.title = '';
                this.item.url_name = '';
                this.item.content = '';
                this.item.info = '';
                this.dialogItemStatus = true;
            },
            itemDel(item) {
                 var _this = this;
                this.$Modal.confirm({
                    title: '消息提示',
                    content: '<p>确定删除么？删除后不可恢复</p>',
                    onOk: () => {
                        _this = this;
                        this.$mip.ajax('{$domain}/widget/ApiAdminWidgetPages/itemDel', {
                            id: item.id,
                        }).then(function(res) {
                            if(res.code == 1) {
                                _this.$Message.success('删除成功');
                                _this.getItemList();
                            }
                        });
                    },
                    onCancel: () => {
                    }
                });
            },
            itemEdit(item) {
                this.item.id = item.id;
                this.dialogItemTitle = '修改{$itemName}';
                this.item.title = item.title;
                this.item.url_name = item.url_name;
                this.item.template = item.template;
                this.editor.setValue(item.content);
                this.item.keywords =  item.keywords;
                this.item.description =  item.description;
                this.dialogItemStatus = true;
            },
            itemPost(val) {
                var _this = this;
                this.$refs[val].validate((valid) => {
                    if(valid) {
                        this.content = this.editor.getValue();
                        if (this.item.id) {
                            this.$mip.ajax('{$domain}/widget/ApiAdminWidgetPages/itemEdit', {
                                id: this.item.id,
                                title: this.item.title,
                                url_name: this.item.url_name,
                                template: this.item.template,
                                content: this.content,
                                keywords: this.item.keywords,
                                description: this.item.description,
                            }).then(function(res) {
                                if(res.code == 1) {
                                    _this.$Message.success('修改成功');
                                    _this.getItemList();
                                    _this.dialogItemStatus = false;
                                }
                            });
                        } else {
                            this.$mip.ajax('{$domain}/widget/ApiAdminWidgetPages/itemAdd', {
                                title: this.item.title,
                                url_name: this.item.url_name,
                                template: this.item.template,
                                content: this.content,
                                keywords: this.item.keywords,
                                description: this.item.description,
                            }).then(function(res) {
                                if(res.code == 1) {
                                    _this.$Message.success('添加成功');
                                    _this.getItemList();
                                    _this.dialogItemStatus = false;
                                }
                            });
                        }
                        
                    }
                });
            },
            getItemList() {
                this.loading = true;
                this.$mip.ajax('{$domain}/widget/ApiAdminWidgetPages/itemList', {
                    page: this.pagination.currentPage,
                    limit: this.pagination.limit,
                }).then(res => {
                    this.itemList = [];
                    if(res.code == 1) {
                        var itemList = res.data.itemList;
                        this.itemList = itemList;
                        this.pagination.total = res.data.total;
                    }
                    this.loading = false;
                });
            },
            
            itemPaginationSelect(val) {
                this.pagination.limit = val;
                this.getItemList();
            },
            itemPaginationClick(val) {
                this.pagination.currentPage = val;
                this.getItemList();
            },
        }
    }
</script>
