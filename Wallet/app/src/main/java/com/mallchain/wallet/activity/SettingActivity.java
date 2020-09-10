package com.mallchain.wallet.activity;

import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.ImageView;
import android.widget.TextView;

import com.mallchain.wallet.MyApplication;
import com.mallchain.wallet.R;

/**
 * Created by Administrator on 2018/8/7.
 */
public class SettingActivity extends Activity {
    private ImageView back;
    private TextView language;
    private Context mContext;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_setting);
        MyApplication.addActivity(this);
        mContext = this;
        back = (ImageView) findViewById(R.id.back);
        language = (TextView) findViewById(R.id.language);
        back.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                finish();
            }
        });
        language.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                startActivity(new Intent(mContext,LanguageActivity.class));
            }
        });
    }

    @Override
    protected void onDestroy() {
        super.onDestroy();
        MyApplication.removeActivity(this);
    }

}
