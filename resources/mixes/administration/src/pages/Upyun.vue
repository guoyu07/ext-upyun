<script>
    import injection from '../helpers/injection';

    export default {
        beforeRouteEnter(to, from, next) {
            next(() => {
                injection.sidebar.active('setting');
            });
        },
        data() {
            const reg1 = /^((?!\d+$)|(?![a-z]+$))(?!^-)[a-z\d-]{5,20}$/;
            const reg2 = /^[a-z\d]{3,60}$/;
            const validatorPublicName = (rule, value, callback) => {
                if (value === '') {
                    callback(new Error('空间名称不能为空'));
                } else if (!reg1.test(value)) {
                    callback(new Error('用户名仅限5-20位,必须以小写英文字符或数字开头,' +
                            '仅支持小写英文字母,数字,中划线组合.(不含特殊字符)'));
                } else {
                    callback();
                }
            };
            const validatorPublicOpName = (rule, value, callback) => {
                if (value === '') {
                    callback(new Error('操作员用户名不能为空'));
                } else if (!reg2.test(value)) {
                    callback(new Error('用户名仅限3-60位小写英文字符和数字(不含特殊字符)'));
                } else {
                    callback();
                }
            };
            const validatorPrivateName = (rule, value, callback) => {
                if (value === '') {
                    callback(new Error('空间名称不能为空'));
                } else if (!reg1.test(value)) {
                    callback(new Error('用户名仅限5-20位,必须以小写英文字符或数字开头,' +
                            '仅支持小写英文字母,数字,中划线组合.(不含特殊字符)'));
                } else {
                    callback();
                }
            };
            const validatorPublicPriName = (rule, value, callback) => {
                if (value === '') {
                    callback(new Error('操作员用户名不能为空'));
                } else if (!reg2.test(value)) {
                    callback(new Error('用户名仅限3-60位小写英文字符和数字(不含特殊字符)'));
                } else {
                    callback();
                }
            };
            return {
                loading: false,
                privateSpace: {
                    bucketName: '',
                    domain: '',
                    folder: '',
                    operatorName: '',
                    operatorPassword: '',
                    token: '',
                    tokenTime: '',
                },
                publicSpace: {
                    bucketName: '',
                    domain: '',
                    folder: '',
                    operatorName: '',
                    operatorPassword: '',
                },
                rulesAudio: {
                    uploadModeAudio: [
                        {
                            message: '上传保存类型不能为空',
                            required: true,
                            trigger: 'blur',
                        },
                    ],
                },
                rulesPrivate: {
                    bucketName: [
                        {
                            required: true,
                            trigger: 'blur',
                            validator: validatorPrivateName,
                        },
                    ],
                    domain: [
                        {
                            message: '远程基本URL不能为空',
                            required: true,
                            trigger: 'blur',
                        },
                        {
                            message: '请输入正确格式的url',
                            trigger: 'blur',
                            type: 'url',
                        },
                    ],
                    folder: [
                        {
                            message: '目录不能为空',
                            required: true,
                            trigger: 'blur',
                        },
                    ],
                    operatorName: [
                        {
                            required: true,
                            trigger: 'blur',
                            validator: validatorPublicPriName,
                        },
                    ],
                    operatorPassword: [
                        {
                            message: '操作员用户密码不能为空',
                            required: true,
                            trigger: 'blur',
                        },
                        {
                            type: 'string',
                            min: 8,
                            message: '密码必须7位以上',
                            trigger: 'blur',
                        },
                    ],
                    token: [
                        {
                            message: '防盗链TOKEN密钥不能为空',
                            required: true,
                            trigger: 'blur',
                        },
                    ],
                },
                rulesPublic: {
                    bucketName: [
                        {
                            required: true,
                            trigger: 'blur',
                            validator: validatorPublicName,
                        },
                    ],
                    domain: [
                        {
                            message: '远程基本URL不能为空',
                            required: true,
                            trigger: 'blur',
                        },
                        {
                            message: '请输入正确格式的url',
                            trigger: 'blur',
                            type: 'url',
                        },
                    ],
                    folder: [
                        {
                            message: '目录不能为空',
                            required: true,
                            trigger: 'blur',
                        },
                    ],
                    operatorName: [
                        {
                            required: true,
                            trigger: 'blur',
                            validator: validatorPublicOpName,
                        },
                    ],
                    operatorPassword: [
                        {
                            message: '操作员用户密码不能为空',
                            required: true,
                            trigger: 'blur',
                        },
                        {
                            type: 'string',
                            min: 8,
                            message: '密码必须7位以上',
                            trigger: 'blur',
                        },
                    ],
                },
                rulesSetImage: {
                    uploadModeImage: [
                        {
                            message: '上传保存类型不能为空',
                            required: true,
                            trigger: 'blur',
                        },
                    ],
                },
                rulesVideo: {
                    uploadModeVideo: [
                        {
                            message: '上传保存类型不能为空',
                            required: true,
                            trigger: 'blur',
                        },
                    ],
                    uploadModeVideoSize: [
                        {
                            message: '上传保存大小不能为空',
                            required: true,
                            trigger: 'blur',
                        },
                    ],
                },
                setAudio: {
                    uploadModeAudio: 'MP3',
                },
                setImage: {
                    uploadModeImage: 'webp75',
                },
                setVideo: {
                    uploadModeVideo: 'ori',
                    uploadModeVideoSize: 'ori',
                },
                waterMark: {
                    enable: true,
                },
            };
        },
        methods: {
            submitPrivateSpace() {
                const self = this;
                self.loading = true;
                self.$refs.privateSpace.validate(valid => {
                    if (valid) {
                        self.$http.post(`${window.api}/cloud/upyun/private/set`, self.privateSpace).then(() => {
                            self.$notice.open({
                                title: '私有空间设置数据成功',
                            });
                        }).catch(() => {}).finally(() => {
                            self.loading = false;
                        });
                    } else {
                        self.loading = false;
                        self.$notice.error({
                            title: '请正确填写设置信息',
                        });
                    }
                });
            },
            submitPublicSpace() {
                const self = this;
                self.loading = true;
                self.$refs.publicSpace.validate(valid => {
                    if (valid) {
                        self.$http.post(`${window.api}/cloud/upyun/set`, self.publicSpace).then(() => {
                            self.$notice.open({
                                title: '公有空间设置数据成功',
                            });
                        }).catch(() => {}).finally(() => {
                            self.loading = false;
                        });
                    } else {
                        self.loading = false;
                        self.$notice.error({
                            title: '请正确填写设置信息',
                        });
                    }
                });
            },
            submitSetAudio() {
                const self = this;
                self.loading = true;
                self.$refs.setAudio.validate(valid => {
                    if (valid) {
                        self.$http.post(`${window.api}/cloud/upyun/mode/audio/set`, self.setAudio).then(() => {
                            self.$notice.open({
                                title: '音频设置数据成功',
                            });
                        }).catch(() => {}).finally(() => {
                            self.loading = false;
                        });
                    } else {
                        self.loading = false;
                        self.$notice.error({
                            title: '请正确填写设置信息',
                        });
                    }
                });
            },
            submitSetImage() {
                const self = this;
                self.loading = true;
                self.$refs.setImage.validate(valid => {
                    if (valid) {
                        self.$http.post(`${window.api}/cloud/upyun/mode/image/set`, self.setImage).then(() => {
                            self.$notice.open({
                                title: '图片设置数据成功',
                            });
                        }).catch(() => {}).finally(() => {
                            self.loading = false;
                        });
                    } else {
                        self.loading = false;
                        self.$notice.error({
                            title: '请正确填写设置信息',
                        });
                    }
                });
            },
            submitSetVideo() {
                const self = this;
                self.loading = true;
                self.$refs.setVideo.validate(valid => {
                    if (valid) {
                        self.$http.post(`${window.api}/cloud/upyun/mode/video/set`, self.setVideo).then(() => {
                            self.$notice.open({
                                title: '视频设置数据成功',
                            });
                        }).catch(() => {}).finally(() => {
                            self.loading = false;
                        });
                    } else {
                        self.loading = false;
                        self.$notice.error({
                            title: '请正确填写设置信息',
                        });
                    }
                });
            },
            submitWaterMark() {
                const self = this;
                self.loading = true;
                self.$http.post(`${window.api}/cloud/upyun/watermark/set`, self.waterMark).then(() => {
                    self.$notice.open({
                        title: '水印设置数据成功',
                    });
                }).catch(() => {}).finally(() => {
                    self.loading = false;
                });
            },
        },
    };
</script>
<template>
    <div class="setting-wrap">
        <div class="cloud-upyun">
            <tabs value="name1">
                <tab-pane label="参数配置" name="name1">
                    <card :bordered="false">
                        <tabs type="card">
                            <tab-pane label="公有空间">
                                <i-form :label-width="180" :model="publicSpace" ref="publicSpace" :rules="rulesPublic">
                                    <row>
                                        <i-col span="12">
                                            <form-item label="空间名称" prop="bucketName">
                                                <i-input v-model="publicSpace.bucketName"></i-input>
                                                <p class="tip">你用于存储文件的空间名称</p>
                                            </form-item>
                                        </i-col>
                                    </row>
                                    <row>
                                        <i-col span="12">
                                            <form-item label="操作员用户名" prop="operatorName">
                                                <i-input v-model="publicSpace.operatorName"></i-input>
                                                <p class="tip">又拍云存储上的操作员用户名</p>
                                            </form-item>
                                        </i-col>
                                    </row>
                                    <row>
                                        <i-col span="12">
                                            <form-item label="操作员用户密码" prop="operatorPassword">
                                                <i-input v-model="publicSpace.operatorPassword"></i-input>
                                                <p class="tip">操作员密码。更新之后不会显示</p>
                                            </form-item>
                                        </i-col>
                                    </row>
                                    <row>
                                        <i-col span="12">
                                            <form-item label="远程基本URL" prop="domain">
                                                <i-input v-model="publicSpace.domain"></i-input>
                                                <p class="tip">远程基本URL，即到你的bucket根目录的URL，结尾不要带/。
                                                    如：http：//XXX.ibenchu.com</p>
                                            </form-item>
                                        </i-col>
                                    </row>
                                    <row>
                                        <i-col span="12">
                                            <form-item label="目录" prop="folder">
                                                <i-input v-model="publicSpace.folder"></i-input>
                                            </form-item>
                                        </i-col>
                                    </row>
                                    <row>
                                        <i-col span="12">
                                            <form-item>
                                                <i-button :loading="loading" type="primary" @click.native="submitPublicSpace">
                                                    <span v-if="!loading">确认提交</span>
                                                    <span v-else>正在提交…</span>
                                                </i-button>
                                            </form-item>
                                        </i-col>
                                    </row>
                                </i-form>
                            </tab-pane>
                            <tab-pane label="私有空间">
                                <i-form :label-width="180" :model="privateSpace" ref="privateSpace" :rules="rulesPrivate">
                                    <row>
                                        <i-col span="12">
                                            <form-item label="空间名称" prop="bucketName">
                                                <i-input v-model="privateSpace.bucketName"></i-input>
                                                <p class="tip">你用于存储文件的空间名称</p>
                                            </form-item>
                                        </i-col>
                                    </row>
                                    <row>
                                        <i-col span="12">
                                            <form-item label="操作员用户名" prop="operatorName">
                                                <i-input v-model="privateSpace.operatorName"></i-input>
                                                <p class="tip">又拍云存储上的操作员用户名</p>
                                            </form-item>
                                        </i-col>
                                    </row>
                                    <row>
                                        <i-col span="12">
                                            <form-item label="操作员用户密码" prop="operatorPassword">
                                                <i-input v-model="privateSpace.operatorPassword"></i-input>
                                                <p class="tip">操作员密码。更新之后不会显示</p>
                                            </form-item>
                                        </i-col>
                                    </row>
                                    <row>
                                        <i-col span="12">
                                            <form-item label="防盗链TOKEN密钥" prop="token">
                                                <i-input v-model="privateSpace.token"></i-input>
                                                <p class="tip">你在又拍云“空间设置”里面设定的防盗链密钥，不启用请勿填写</p>
                                            </form-item>
                                        </i-col>
                                    </row>
                                    <row>
                                        <i-col span="12">
                                            <form-item label="防盗链超时（s）" prop="tokenTime">
                                                <i-input v-model="privateSpace.tokenTime"></i-input>
                                                <p class="tip">防盗链超时（没有开启防盗链的不用设置）。即从网页开始显示
                                                    到链接超时的时间间隔。超时以后，链接便会失效。如果是比较大的文件，如音
                                                    乐、电影等，你必须考虑用户播放此文件或下载此文件需要多长时间，超时以后
                                                    链接将会自动失效</p>
                                            </form-item>
                                        </i-col>
                                    </row>
                                    <row>
                                        <i-col span="12">
                                            <form-item label="远程基本URL" prop="domain">
                                                <i-input v-model="privateSpace.domain"></i-input>
                                                <p class="tip">远程基本URL，即到你的bucket根目录的URL，结尾不要带/。
                                                    如：http：//XXX.ibenchu.com</p>
                                            </form-item>
                                        </i-col>
                                    </row>
                                    <row>
                                        <i-col span="12">
                                            <form-item label="目录" prop="folder">
                                                <i-input v-model="privateSpace.folder"></i-input>
                                            </form-item>
                                        </i-col>
                                    </row>
                                    <row>
                                        <i-col span="12">
                                            <form-item>
                                                <i-button :loading="loading" type="primary"
                                                          @click.native="submitPrivateSpace">
                                                    <span v-if="!loading">确认提交</span>
                                                    <span v-else>正在提交…</span>
                                                </i-button>
                                            </form-item>
                                        </i-col>
                                    </row>
                                </i-form>
                            </tab-pane>
                            <tab-pane label="水印设置">
                                <i-form :label-width="180" :model="waterMark" ref="waterMark" :rules="rules">
                                    <row>
                                        <i-col span="12">
                                            <form-item label="是否添加水印" prop="enable">
                                                <i-switch size="large" v-model="waterMark.enable">
                                                    <span slot="open">开启</span>
                                                    <span slot="close">关闭</span>
                                                </i-switch>
                                            </form-item>
                                        </i-col>
                                    </row>
                                    <row>
                                        <i-col span="12">
                                            <form-item>
                                                <i-button :loading="loading" type="primary"
                                                          @click.native="submitWaterMark">
                                                    <span v-if="!loading">确认提交</span>
                                                    <span v-else>正在提交…</span>
                                                </i-button>
                                            </form-item>
                                        </i-col>
                                    </row>
                                </i-form>
                            </tab-pane>
                            <tab-pane label="图片设置">
                                <i-form :label-width="180" :model="setImage" ref="setImage" :rules="rulesSetImage">
                                    <row>
                                        <i-col span="12">
                                            <form-item label="上传保存类型" prop="uploadModeImage">
                                                <radio-group v-model="setImage.uploadModeImage">
                                                    <radio label="webp75">webp有损</radio>
                                                    <radio label="webp100">webp无损</radio>
                                                    <radio label="ori">原图</radio>
                                                </radio-group>
                                            </form-item>
                                        </i-col>
                                    </row>
                                    <row>
                                        <i-col span="12">
                                            <form-item>
                                                <i-button :loading="loading" type="primary" @click.native="submitSetImage">
                                                    <span v-if="!loading">确认提交</span>
                                                    <span v-else>正在提交…</span>
                                                </i-button>
                                            </form-item>
                                        </i-col>
                                    </row>
                                </i-form>
                            </tab-pane>
                            <tab-pane label="音频设置">
                                <i-form :label-width="180" :model="setAudio" ref="setAudio" :rules="rulesAudio">
                                    <row>
                                        <i-col span="12">
                                            <form-item label="上传保存类型" prop="uploadModeAudio">
                                                <radio-group v-model="setAudio.uploadModeAudio">
                                                    <radio label="MP3">MP3</radio>
                                                    <radio label="AAC">AAC</radio>
                                                    <radio label="ori">不转换</radio>
                                                </radio-group>
                                            </form-item>
                                        </i-col>
                                    </row>
                                    <row>
                                        <i-col span="12">
                                            <form-item>
                                                <i-button :loading="loading" type="primary" @click.native="submitSetAudio">
                                                    <span v-if="!loading">确认提交</span>
                                                    <span v-else>正在提交…</span>
                                                </i-button>
                                            </form-item>
                                        </i-col>
                                    </row>
                                </i-form>
                            </tab-pane>
                            <tab-pane label="视频设置">
                                <i-form :label-width="180" :model="setVideo" ref="setVideo" :rules="rulesVideo">
                                    <row>
                                        <i-col span="12">
                                            <form-item label="上传保存类型" prop="uploadModeVideo">
                                                <radio-group v-model="setVideo.uploadModeVideo">
                                                    <radio label="ori">不转换</radio>
                                                    <radio label="VP9">VP9</radio>
                                                    <radio label="H264">H264</radio>
                                                    <radio label="H265">H265</radio>
                                                </radio-group>
                                            </form-item>
                                        </i-col>
                                    </row>
                                    <row>
                                        <i-col span="12">
                                            <form-item label="上传保存大小" prop="uploadModeVideoSize">
                                                <radio-group v-model="setVideo.uploadModeVideoSize">
                                                    <radio label="ori">不转换</radio>
                                                    <radio label="1080">1080p</radio>
                                                    <radio label="720">720p</radio>
                                                    <radio label="480">480p</radio>
                                                </radio-group>
                                            </form-item>
                                        </i-col>
                                    </row>
                                    <row>
                                        <i-col span="12">
                                            <form-item>
                                                <i-button :loading="loading" type="primary" @click.native="submitSetVideo">
                                                    <span v-if="!loading">确认提交</span>
                                                    <span v-else>正在提交…</span>
                                                </i-button>
                                            </form-item>
                                        </i-col>
                                    </row>
                                </i-form>
                            </tab-pane>
                        </tabs>
                    </card>
                </tab-pane>
            </tabs>
        </div>
    </div>
</template>
