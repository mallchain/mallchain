package com.mallchain.wallet.activity;

import android.app.Activity;
import android.os.Bundle;
import android.text.TextUtils;
import android.view.View;
import android.widget.ImageView;
import android.widget.TextView;

import com.mallchain.wallet.MyApplication;
import com.mallchain.wallet.R;

/**
 * Created by Administrator on 2018/8/7.
 */
public class WebViewActivity extends Activity {
    private TextView textView;
    private ImageView back;
    private TextView title;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        MyApplication.addActivity(this);
        setContentView(R.layout.activity_webview);
        back = (ImageView) findViewById(R.id.back);
        title = (TextView) findViewById(R.id.title);
        textView = (TextView) findViewById(R.id.text);
        String titleStr = getIntent().getStringExtra("title");
        title.setText(titleStr);
        if (TextUtils.equals(getString(R.string.gas_cost),titleStr)){
            textView.setText(getString(R.string.oapc));
        }else{
        textView.setText(getString(R.string.wig));
        }

        back.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {

                finish();
            }
        });
    }

    @Override
    protected void onDestroy() {
        super.onDestroy();
        MyApplication.removeActivity(this);
    }
}
